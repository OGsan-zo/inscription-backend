<?php

namespace App\Repository\notes;

use App\Entity\Semestres;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class SemestresRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semestres::class);
    }

    public function findByNiveau(int $idNiveau): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.niveau = :idNiveau')
            ->andWhere('s.deletedAt IS NULL')
            ->setParameter('idNiveau', $idNiveau)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
