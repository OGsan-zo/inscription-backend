<?php

namespace App\Service\notes;

use App\Dto\notes\MatiereDto;
use App\Dto\notes\MatiereSemestreDto;
use App\Dto\utils\OrderCriteria;
use App\Entity\Matieres;
use App\Entity\Mentions;
use App\Entity\Semestres;
use App\Repository\notes\MatieresRepository;
use App\Repository\notes\SemestresRepository;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class NotesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly MatieresRepository $matieresRepository,
        private readonly SemestresRepository $semestresRepository,
        private readonly ValidationService $validationService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): MatieresRepository
    {
        return $this->matieresRepository;
    }

    // -------------------------------------------------------
    // Semestres
    // -------------------------------------------------------

    public function getAllSemestres(): array
    {
        return $this->semestresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

    public function getVerifiedSemestre(int $id): Semestres
    {
        $semestre = $this->semestresRepository->find($id);
        $this->validationService->throwIfNull($semestre, "Semestre introuvable pour l'ID $id.");
        return $semestre;
    }

    public function formatSemestre(Semestres $s): array
    {
        return $s->toArray(['deletedAt', 'createdAt', 'grade']);
    }

    public function formatAllSemestres(array $semestres): array
    {
        return array_map(fn(Semestres $s) => $this->formatSemestre($s), $semestres);
    }

    // -------------------------------------------------------
    // Matieres
    // -------------------------------------------------------

    public function getAllMatieres(): array
    {
        return $this->matieresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

    public function getVerifiedMatiere(int $id): Matieres
    {
        $matiere = $this->getById($id);
        $this->validationService->throwIfNull($matiere, "Matière introuvable pour l'ID $id.");
        return $matiere;
    }

    public function createMatiere(MatiereDto $dto): Matieres
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere = new Matieres();
            $matiere->setNom($dto->nom);
            $this->save($matiere);
            $this->em->getConnection()->commit();
            return $matiere;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function format(Matieres $m): array
    {
        return $m->toArray(['deletedAt', 'createdAt']);
    }

    public function formatAll(array $matieres): array
    {
        return array_map(fn(Matieres $m) => $this->format($m), $matieres);
    }

    // -------------------------------------------------------
    // Mentions
    // -------------------------------------------------------

    public function getAllMentions(): array
    {
        return $this->em->getRepository(Mentions::class)->findAll();
    }

    public function getVerifiedMention(int $id): Mentions
    {
        $mention = $this->em->getRepository(Mentions::class)->find($id);
        $this->validationService->throwIfNull($mention, "Mention introuvable pour l'ID $id.");
        return $mention;
    }

    public function formatMention(Mentions $m): array
    {
        return [
            'id'  => $m->getId(),
            'nom' => $m->getNom(),
            'abr' => $m->getAbr(),
        ];
    }

    public function formatAllMentions(array $mentions): array
    {
        return array_map(fn(Mentions $m) => $this->formatMention($m), $mentions);
    }

    // -------------------------------------------------------
    // Matiere-Semestres (liaison)
    // -------------------------------------------------------

    public function getAllMatiereSemestres(): array
    {
        return $this->matieresRepository->findAvecSemestre();
    }

    public function assignerSemestre(MatiereSemestreDto $dto): Matieres
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere  = $this->getVerifiedMatiere($dto->idMatiere);
            $semestre = $this->getVerifiedSemestre($dto->idSemestre);

            if ($matiere->getSemestre() !== null) {
                throw new \Exception(
                    "La matière \"{$matiere->getNom()}\" est déjà associée à un semestre.",
                    400
                );
            }

            $matiere->setSemestre($semestre);
            $this->save($matiere);
            $this->em->getConnection()->commit();
            return $matiere;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function formatMatiereSemestre(Matieres $m): array
    {
        return [
            'id'       => $m->getId(),
            'matiere'  => ['id' => $m->getId(), 'nom' => $m->getNom()],
            'semestre' => [
                            'id'  => $m->getSemestre()?->getId(),
                            'nom' => $m->getSemestre()?->getNom(),
                            ],
        ];
    }

    public function formatAllMatiereSemestres(array $matieres): array
    {
        return array_map(fn(Matieres $m) => $this->formatMatiereSemestre($m), $matieres);
    }
}
