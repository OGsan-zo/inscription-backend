<?php

namespace App\Service\notes;

use App\Dto\notes\affiche\NoteAfficheDto;
use App\Dto\notes\affiche\NoteListeDto;
use App\Dto\notes\affiche\NoteTypeDto;
use App\Dto\notes\NoteInsetionListeDto;
use App\Entity\note\TypeNotes;
use App\Entity\proposEtudiant\Etudiants;
use App\Entity\note\MatiereMentionCoefficient;
use App\Entity\note\Notes;
use App\Repository\notes\NotesRepository;
use App\Service\notes\view\VueCoefficientDetailsService;
use App\Service\notes\view\VueNotesMaxService;
use App\Service\proposEtudiant\EtudiantsService;
use App\Service\proposEtudiant\NiveauEtudiantsService; 
use App\Service\utils\BaseValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class NotesService extends BaseValidationService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly NotesRepository $notesRepository,
        private readonly CoefficientsService $coefficientsService,
        private readonly SemestresService $semestresService,
        private readonly EtudiantsService $etudiantsService,
        private readonly NiveauEtudiantsService $niveauEtudiantsService,
        private readonly TypeNotesService $typeNotesService,
        private readonly VueCoefficientDetailsService $vueCoefficientDetailsService,
        private readonly VueNotesMaxService $vueNotesMaxService
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): NotesRepository
    {
        return $this->notesRepository;
    }
    public function saveNoteEtudiant(Etudiants $etudiant, MatiereMentionCoefficient $mmc,TypeNotes $typeNote,float $valeur,int $annee,bool $isValid = false): Notes
    {
        $result = new Notes();
        $result->setEtudiant($etudiant);
        $result->setMatiereMentionCoefficient($mmc);
        $result->setTypeNote($typeNote);
        $result->setValeur($valeur);
        $result->setAnnee($annee);
        if ($isValid) {
            $result->setDateValidation(new \DateTime());
        }
        $result = $this->save($result);
        return $result;
    }
    public function saveNoteEtudiantId(int $etudiantId,int $matiereCoefficientId,int $typeNoteId,float $valeur,int $annee,bool $isValid = false):Notes
    {
        $etudiant = $this->etudiantsService->getOrFailUtilisateur($etudiantId);
        $matiereCoefficient = $this->coefficientsService->getVerifierById($matiereCoefficientId);
        $typeNote = $this->typeNotesService->getVerifierById($typeNoteId);
        $result = $this->saveNoteEtudiant($etudiant,$matiereCoefficient,$typeNote,$valeur,$annee,$isValid);
        return $result;
    }
    public function insertListeNoteProfesseurDto(NoteInsetionListeDto $dto): array
    {
        $result = [];
        $typeNoteId = 1;
        if (!$dto->isNormale) {
            $typeNoteId = 2;
        }
        $this->em->getConnection()->beginTransaction();
        try {
            foreach($dto->listeEtudiants as $etudiant)
            {
                $note = $this->saveNoteEtudiantId($etudiant['etudiantId'], $dto->idMatiereCoefficient, $typeNoteId, $etudiant['valeur'], $dto->annee);
                $result[] = $note;
            }
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
        return $result;
    }
    public function getNoteEtudiant(int $etudiantId, int $semestreId): array
    {

        $result = [];
        $noteTypeNormaleDto = new NoteTypeDto();
        $noteTypeNormaleDto->setType("Normale");

        $noteTypeRattrapageDto = new NoteTypeDto();
        $noteTypeRattrapageDto->setType("Rattrapage");

        $noteTypeFinalDto = new NoteTypeDto();
        $noteTypeFinalDto->setType("Final");
        $etudiant = $this->etudiantsService->getOrFailUtilisateur($etudiantId);
        $niveau_etudiants = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($etudiant);
        $mentionId = $niveau_etudiants->getMention()->getId();
        $listeMatiereCoeffDetail = $this->vueCoefficientDetailsService->getBySemestreIdMentionIdGroupedByUe($semestreId, $mentionId);
        $noteListeNormales= [];
        $noteListeRattrappages = [];
        $noteListeFinals= [];

        $sommeMoyenneNormale = 0;
        $sommeMoyenneRatrrapage = 0;
        $sommeMoyenneFinal = 0;

        $nbUe =0;
        foreach($listeMatiereCoeffDetail as $ue)
        {
            $noteListeNormaleDto = new NoteListeDto();
            $noteListeNormaleDto->setUe($ue->getUe());

            $noteListeRattrappage = new NoteListeDto();
            $noteListeRattrappage->setUe($ue->getUe());

            $noteListeFinal = new NoteListeDto();
            $noteListeFinal->setUe($ue->getUe());

            foreach($ue->getMatiereCoefficients() as $matiereCoeff)
            {                
                $noteAfficheNormaleDto = new NoteAfficheDto();
                $noteAfficheNormaleDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheNormaleDto->setCoefficient($matiereCoeff->getCoefficient());
               
                $noteAfficheRattrappageDto = new NoteAfficheDto();
                $noteAfficheRattrappageDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheRattrappageDto->setCoefficient($matiereCoeff->getCoefficient());

                $noteAfficheFinalDto = new NoteAfficheDto();
                $noteAfficheFinalDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheFinalDto->setCoefficient($matiereCoeff->getCoefficient());

                #Pour avoir le dernier note session normale
                $dernierNotesNormale = $this->vueNotesMaxService->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(),1);
                $noteNormale = $dernierNotesNormale?->getValeur() ?? null;
                $noteAfficheNormaleDto->setNote($noteNormale);
                $noteAfficheNormaleDto->setNoteAvecCoefficient($noteNormale * $matiereCoeff->getCoefficient());
                $noteListeNormaleDto->ajouterNote($noteAfficheNormaleDto);
               
                #Pour note rattrappage
                $dernierNotesRattrappage = $this->vueNotesMaxService->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(),2);
                $noteRattrappage = $dernierNotesRattrappage?->getValeur() ?? null;
                $noteAfficheRattrappageDto->setNote($noteRattrappage);
                $noteAfficheRattrappageDto->setNoteAvecCoefficient($noteRattrappage * $matiereCoeff->getCoefficient());
                $noteListeRattrappage->ajouterNote($noteAfficheRattrappageDto);

                #Pour le note final
                $noteFinal = max($noteNormale, $noteRattrappage);
                $noteAfficheFinalDto->setNote($noteFinal);
                $noteAfficheFinalDto->setNoteAvecCoefficient($noteFinal * $matiereCoeff->getCoefficient());
                $noteListeFinal->ajouterNote($noteAfficheFinalDto);

            }
            $noteListeNormaleDto->calculerSommeCoefficientsNotes();
            $sommeMoyenneNormale += $noteListeNormaleDto->getMoyenne();

            $noteListeRattrappage->calculerSommeCoefficientsNotes();
            $sommeMoyenneRatrrapage += $noteListeRattrappage->getMoyenne();

            $noteListeFinal->calculerSommeCoefficientsNotes();
            $sommeMoyenneFinal += $noteListeFinal->getMoyenne();
            
            $noteListeNormales[] = $noteListeNormaleDto;
            $noteListeRattrappages[] = $noteListeRattrappage;
            $noteListeFinals[] = $noteListeFinal;

            $nbUe ++;
            
        }
        $noteTypeNormaleDto->setNotesListes($noteListeNormales); 
        $noteTypeNormaleDto->setMoyenne($sommeMoyenneNormale / $nbUe);
       
        $noteTypeRattrapageDto->setNotesListes($noteListeRattrappages);  
        $noteTypeRattrapageDto->setMoyenne($sommeMoyenneRatrrapage / $nbUe);
       
        $noteTypeFinalDto->setNotesListes($noteListeFinals);
        $noteTypeFinalDto->setMoyenne($sommeMoyenneFinal / $nbUe);
        $result[] = $noteTypeNormaleDto;
        $result[] = $noteTypeRattrapageDto;  
        $result[] = $noteTypeFinalDto;
        return $result;
        
    }
}
