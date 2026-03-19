<?php

namespace App\Service\notes;

use App\Dto\utils\OrderCriteria;
use App\Entity\note\Semestres;
use App\Repository\notes\SemestresRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class SemestresService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly SemestresRepository $semestresRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): SemestresRepository
    {
        return $this->semestresRepository;
    }

    public function getAllSemestres(): array
    {
        return $this->semestresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

}
