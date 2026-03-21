<?php

namespace App\Service\notes;

use App\Dto\notes\NoteInsetionListeDto;
use App\Dto\notes\NoteUpdateDto;
use App\Entity\note\TypeNotes;
use App\Entity\proposEtudiant\Etudiants;
use App\Entity\note\MatiereMentionCoefficient;
use App\Entity\proposEtudiant\NiveauEtudiants;
use App\Entity\note\Notes;
use App\Repository\notes\NotesRepository;
use App\Service\proposEtudiant\EtudiantsService;
use App\Service\proposEtudiant\NiveauEtudiantsService; 
use App\Service\utils\BaseValidationService;
use App\Service\utils\ValidationService;
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
        private readonly TypeNotesService $typeNotesService
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): NotesRepository
    {
        return $this->notesRepository;
    }

    
    public function getDernierNiveauEtudiant(Etudiants $e): NiveauEtudiants
    {
        $ne = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($e);
        $this->throwIfNull($ne, "Aucun niveau trouvé pour l'étudiant ID {$e->getId()}.");
        return $ne;
    }

    public function formatEtudiantPourNotes(Etudiants $e, NiveauEtudiants $ne): array
    {
        return [
            'id'      => $e->getId(),
            'nom'     => $e->getNom(),
            'prenom'  => $e->getPrenom(),
            'mention' => $ne->getMention()?->getNom(),
            'niveau'  => $ne->getNiveau()?->getNom(),
        ];
    }

    // -------------------------------------------------------
    // Résultats
    // -------------------------------------------------------

    public function getResultatsEtudiant(int $idEtudiant, int $idSemestre): array
    {
        $etudiant     = $this->getVerifierById($idEtudiant);
        $ne           = $this->getDernierNiveauEtudiant($etudiant);
        $mention      = $ne->getMention();

        $coefficients = $this->coefficientsService->findByMentionAndSemestre(
            $mention->getId(),
            $idSemestre
        );

        $notes = [];
        foreach ($coefficients as $mmc) {
            $note    = $this->notesRepository->findByEtudiantAndMMC($etudiant->getId(), $mmc->getId());
            $notes[] = $this->formatLigneResultat($mmc, $note);
        }

        return [
            'etudiant' => $this->formatEtudiantPourNotes($etudiant, $ne),
            'semestre' => $this->semestresService->formatSemestre(
                $this->semestresService->getVerifierById($idSemestre)
            ),
            'notes'    => $notes,
        ];
    }

    public function formatLigneResultat(MatiereMentionCoefficient $mmc, ?Notes $note): array
    {
        $matiere     = $mmc->getMatiere();
        $coefficient = $mmc->getCoefficient();
        $valeur      = $note !== null ? (float) $note->getValeur() : null;
        $ecAvecCoef  = $valeur !== null ? round($valeur * $coefficient, 2) : null;
        $resultat    = $valeur !== null ? ($valeur >= 10 ? 'Validé' : 'Non validé') : null;

        return [
            'idNote'      => $note?->getId(),
            'idMatiere'   => $matiere?->getId(),
            'nomMatiere'  => $matiere?->getName(),
            'coefficient' => $coefficient,
            'noteSur20'   => $valeur,
            'ecAvecCoef'  => $ecAvecCoef,
            'resultat'    => $resultat,
        ];
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
}
