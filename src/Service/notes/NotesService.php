<?php

namespace App\Service\notes;

use App\Dto\notes\NoteUpdateDto;
use App\Entity\proposEtudiant\Etudiants;
use App\Entity\note\MatiereMentionCoefficient;
use App\Entity\proposEtudiant\NiveauEtudiants;
use App\Entity\note\Notes;
use App\Repository\notes\NotesRepository;
use App\Service\proposEtudiant\EtudiantsService;
use App\Service\proposEtudiant\NiveauEtudiantsService;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class NotesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly NotesRepository $notesRepository,
        private readonly CoefficientsService $coefficientsService,
        private readonly SemestresService $semestresService,
        private readonly EtudiantsService $etudiantsService,
        private readonly NiveauEtudiantsService $niveauEtudiantsService,
        private readonly ValidationService $validationService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): NotesRepository
    {
        return $this->notesRepository;
    }

    // -------------------------------------------------------
    // Étudiants (helpers)
    // -------------------------------------------------------

    public function getVerifiedEtudiant(int $id): Etudiants
    {
        $etudiant = $this->etudiantsService->getEtudiantById($id);
        $this->validationService->throwIfNull($etudiant, "Étudiant introuvable pour l'ID $id.");
        return $etudiant;
    }

    public function getDernierNiveauEtudiant(Etudiants $e): NiveauEtudiants
    {
        $ne = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($e);
        $this->validationService->throwIfNull($ne, "Aucun niveau trouvé pour l'étudiant ID {$e->getId()}.");
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

    public function getResultatsEtudiant(int $idEtudiant, ?int $idSemestre): array
    {
        $etudiant     = $this->getVerifiedEtudiant($idEtudiant);
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

    public function updateNote(int $id, NoteUpdateDto $dto): Notes
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $note = $this->getVerifierById($id);
            $note->setValeur((string) $dto->valeur);
            $this->em->flush();
            $this->em->getConnection()->commit();
            return $note;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
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
            'nomMatiere'  => $matiere?->getNom(),
            'coefficient' => $coefficient,
            'noteSur20'   => $valeur,
            'ecAvecCoef'  => $ecAvecCoef,
            'resultat'    => $resultat,
        ];
    }
}
