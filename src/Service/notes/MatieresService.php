<?php

namespace App\Service\notes;

use App\Dto\notes\MatiereDto;
use App\Dto\utils\OrderCriteria;
use App\Entity\note\Matieres;
use App\Repository\notes\MatieresRepository;
use App\Service\notes\SemestresService;
use App\Service\notes\UEService;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class MatieresService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly MatieresRepository $matieresRepository,
        private readonly SemestresService $semestresService,
        private readonly UEService $ueService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): MatieresRepository
    {
        return $this->matieresRepository;
    }

    // -------------------------------------------------------
    // Matieres
    // -------------------------------------------------------

    public function getAllMatieres(): array
    {
        return $this->matieresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

    public function createMatiere(MatiereDto $dto): Matieres
    {
        try {
            $matiere = new Matieres();
            $matiere->setName($dto->name);
            $matiere->setSemestre($this->semestresService->getVerifierById($dto->semestreId));
            $matiere->setUe($this->ueService->getVerifierById($dto->ueId));
            $this->save($matiere);
            return $matiere;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
    public function updateMatiere(int $id, MatiereDto $dto): Matieres
    {
        try {
            $matiere = $this->getVerifierById($id);
            $matiere->setName($dto->name);
            $matiere->setSemestre($this->semestresService->getVerifierById($dto->semestreId));
            $matiere->setUe($this->ueService->getVerifierById($dto->ueId));
            $this->save($matiere);
            return $matiere;
        } catch (\Throwable $e) {
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
    // Liaison Matière–Semestre
    // -------------------------------------------------------

    public function getAllMatiereSemestres(): array
    {
        return $this->matieresRepository->findAvecSemestre();
    }

}
