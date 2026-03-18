<?php

namespace App\Service\notes;

use App\Dto\utils\OrderCriteria;
use App\Entity\Semestres;
use App\Repository\notes\SemestresRepository;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class SemestresService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly SemestresRepository $semestresRepository,
        ValidationService $validationService,
    ) {
        parent::__construct($em, $validationService);
    }

    protected function getRepository(): SemestresRepository
    {
        return $this->semestresRepository;
    }

    public function getAllSemestres(): array
    {
        return $this->semestresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

    public function formatSemestre(Semestres $s): array
    {
        return $s->toArray(['deletedAt', 'createdAt', 'grade']);
    }

    public function formatAllSemestres(array $semestres): array
    {
        return array_map(fn(Semestres $s) => $this->formatSemestre($s), $semestres);
    }
}
