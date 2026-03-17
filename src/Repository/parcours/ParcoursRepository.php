<?php

namespace App\Repository\parcours;

use App\Entity\Parcours;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\NiveauEtudiants;

class ParcoursRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcours::class);
    }

    public function findByMentionAndNiveau(int $idMention, int $idNiveau): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.mention = :idMention')
            ->andWhere('p.niveau = :idNiveau')
            ->andWhere('p.deletedAt IS NULL')
            ->setParameter('idMention', $idMention)
            ->setParameter('idNiveau', $idNiveau)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getEtudiantsByMentionAndNiveau(int $idMention, int $idNiveau): array
    {
        return $this->em->getRepository(NiveauEtudiants::class)
            ->createQueryBuilder('ne')
            ->andWhere('ne.mention = :idMention')
            ->andWhere('ne.niveau = :idNiveau')
            ->andWhere('ne.deletedAt IS NULL')
            ->setParameter('idMention', $idMention)
            ->setParameter('idNiveau', $idNiveau)
            ->orderBy('ne.dateInsertion', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
