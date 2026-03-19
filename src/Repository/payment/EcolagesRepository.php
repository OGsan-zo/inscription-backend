<?php

namespace App\Repository\payment;

use App\Entity\payment\Ecolages;
use App\Entity\proposEtudiant\Formations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ecolages>
 */
class EcolagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecolages::class);
    }


    public function getEcolageParFormation(Formations $formation): ?Ecolages
    {
        return $this->createQueryBuilder('e')
            ->where('e.formations = :formation')
            ->setParameter('formation', $formation)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
