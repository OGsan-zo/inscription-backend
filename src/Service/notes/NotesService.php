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
    public function getNoteEtudiant(int $etudiantId, int $semestreId, bool $isCalculerParCredit = false): array
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

        $listeMatiereCoeffDetail = $this->vueCoefficientDetailsService
            ->getBySemestreIdMentionIdGroupedByUe($semestreId, $mentionId);

        $noteListeNormales = [];
        $noteListeRattrappages = [];
        $noteListeFinals = [];

        $sommeMoyenneNormale = 0;
        $sommeMoyenneRatrrapage = 0;
        $sommeMoyenneFinal = 0;

        $nbUe = 0;

        foreach ($listeMatiereCoeffDetail as $ue) {

            $noteListeNormaleDto = new NoteListeDto();
            $noteListeNormaleDto->setUe($ue->getUe());

            $noteListeRattrappage = new NoteListeDto();
            $noteListeRattrappage->setUe($ue->getUe());

            $noteListeFinal = new NoteListeDto();
            $noteListeFinal->setUe($ue->getUe());

            foreach ($ue->getMatiereCoefficients() as $matiereCoeff) {

                // 🔥 choix dynamique : coefficient ou crédit
                $poids = $isCalculerParCredit 
                    ? $matiereCoeff->getCredit() 
                    : $matiereCoeff->getCoefficient();

                $noteAfficheNormaleDto = new NoteAfficheDto();
                $noteAfficheNormaleDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheNormaleDto->setCoefficient($matiereCoeff->getCoefficient());
                $noteAfficheNormaleDto->setCredit($matiereCoeff->getCredit());

                $noteAfficheRattrappageDto = new NoteAfficheDto();
                $noteAfficheRattrappageDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheRattrappageDto->setCoefficient($matiereCoeff->getCoefficient());
                $noteAfficheRattrappageDto->setCredit($matiereCoeff->getCredit());

                $noteAfficheFinalDto = new NoteAfficheDto();
                $noteAfficheFinalDto->setMatiere($matiereCoeff->getMatiereNom());
                $noteAfficheFinalDto->setCoefficient($matiereCoeff->getCoefficient());
                $noteAfficheFinalDto->setCredit($matiereCoeff->getCredit());

                // ===== NORMALE =====
                $dernierNotesNormale = $this->vueNotesMaxService
                    ->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(), 1);

                $noteNormale = $dernierNotesNormale?->getValeur() ?? null;

                $noteAfficheNormaleDto->setNote($noteNormale);
                $noteAfficheNormaleDto->setNoteAvecCoefficient(
                    $noteNormale !== null ? $noteNormale * $poids : null
                );

                $noteListeNormaleDto->ajouterNote($noteAfficheNormaleDto);

                // ===== RATTRAPAGE =====
                $dernierNotesRattrappage = $this->vueNotesMaxService
                    ->getByMatiereCoefficientIdEtudiant($etudiantId, $matiereCoeff->getId(), 2);

                $noteRattrappage = $dernierNotesRattrappage?->getValeur() ?? null;

                $noteAfficheRattrappageDto->setNote($noteRattrappage);
                $noteAfficheRattrappageDto->setNoteAvecCoefficient(
                    $noteRattrappage !== null ? $noteRattrappage * $poids : null
                );

                $noteListeRattrappage->ajouterNote($noteAfficheRattrappageDto);

                // ===== FINAL =====
                $noteFinal = max($noteNormale ?? 0, $noteRattrappage ?? 0);

                $noteAfficheFinalDto->setNote($noteFinal);
                $noteAfficheFinalDto->setNoteAvecCoefficient($noteFinal * $poids);

                $noteListeFinal->ajouterNote($noteAfficheFinalDto);
            }

            // ⚠️ IMPORTANT : adapter aussi cette méthode !
            $noteListeNormaleDto->calculerSommeCoefficientsNotes($isCalculerParCredit);
            $sommeMoyenneNormale += $noteListeNormaleDto->getMoyenne();

            $noteListeRattrappage->calculerSommeCoefficientsNotes($isCalculerParCredit);
            $sommeMoyenneRatrrapage += $noteListeRattrappage->getMoyenne();

            $noteListeFinal->calculerSommeCoefficientsNotes($isCalculerParCredit);
            $sommeMoyenneFinal += $noteListeFinal->getMoyenne();

            $noteListeNormales[] = $noteListeNormaleDto;
            $noteListeRattrappages[] = $noteListeRattrappage;
            $noteListeFinals[] = $noteListeFinal;

            $nbUe++;
        }

        $noteTypeNormaleDto->setNotesListes($noteListeNormales);
        $noteTypeRattrapageDto->setNotesListes($noteListeRattrappages);
        $noteTypeFinalDto->setNotesListes($noteListeFinals);

        if ($nbUe > 0) {
            $noteTypeNormaleDto->setMoyenne($sommeMoyenneNormale / $nbUe);
            $noteTypeRattrapageDto->setMoyenne($sommeMoyenneRatrrapage / $nbUe);
            $noteTypeFinalDto->setMoyenne($sommeMoyenneFinal / $nbUe);
        }

        $result[] = $noteTypeNormaleDto;
        $result[] = $noteTypeRattrapageDto;
        $result[] = $noteTypeFinalDto;

        return $result;
    }
}
