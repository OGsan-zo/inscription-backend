<?php

namespace App\Repository\notes;

use App\Entity\Matieres;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatieresRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matieres::class);
    }

    public function findBySemestre(int $idSemestre): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.semestre = :idSemestre')
            ->andWhere('m.deletedAt IS NULL')
            ->setParameter('idSemestre', $idSemestre)
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAvecSemestre(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.semestre IS NOT NULL')
            ->andWhere('m.deletedAt IS NULL')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
