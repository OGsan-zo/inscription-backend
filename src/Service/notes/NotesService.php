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
    public function getNoteEtudiant(int $etudiantId, int $semestreId): NoteTypeDto
    {
        $noteTypeNormaleDto = new NoteTypeDto();
        $noteTypeNormaleDto->setType("Normale");
        // $noteTypeRattrapageDto = new NoteTypeDto();
        // $noteTypeRattrapageDto->setType("Rattrapage");
        $etudiant = $this->etudiantsService->getOrFailUtilisateur($etudiantId);
        $niveau_etudiants = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($etudiant);
        $mentionId = $niveau_etudiants->getMention()->getId();
        $listeMatiereCoeffDetail = $this->vueCoefficientDetailsService->getBySemestreIdMentionIdGroupedByUe($semestreId, $mentionId);
        $noteListeNormales= [];
        foreach($listeMatiereCoeffDetail as $ue)
        {
            $noteListeNormaleDto = new NoteListeDto();
            $noteListeNormaleDto->setUe($ue->getUe());
            foreach($ue as $matiereCoeff)
            {
                $listeNote = $this->vueNotesMaxService->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(), 1);
                foreach($listeNote as $note)
                {
                    $noteAfficheNormaleDto = new NoteAfficheDto();
                    $noteAfficheNormaleDto->setMatiere($note->getMatiere());
                    $noteAfficheNormaleDto->setCoefficient($note->getCoefficient());
               
                    #Pour avoir le dernier note session normale
                    $dernierNotes = $this->vueNotesMaxService->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(),1);
                    $noteNormale = $dernierNotes?->getValeur() ?? null;
                    $noteAfficheNormaleDto->setNote($noteNormale);
                    $noteAfficheNormaleDto->setNoteAvecCoefficient($noteNormale * $note->getCoefficient());
                    $noteListeNormaleDto->ajouterNote($noteAfficheNormaleDto);
                }


            }
            $noteListeNormaleDto->calculerSommeCoefficientsNotes();
            $noteListeNormaleDto->calculerMoyenne();
            $noteListeNormales[] = $noteListeNormaleDto;
            
        }
        $noteTypeNormaleDto->setNotesListes($noteListeNormales);   
        return $noteTypeNormaleDto;
        
    }
}
