<?php

namespace App\Repository\notes;

use App\Entity\note\MatiereMentionCoefficient;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatiereMentionCoefficientRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatiereMentionCoefficient::class);
    }

    public function findByMatiereAndMention(int $idMatiere, int $idMention,int $idNiveau): ?MatiereMentionCoefficient
    {
        return $this->createQueryBuilder('mmc')
            ->andWhere('mmc.matiere = :idMatiere')
            ->andWhere('mmc.mention = :idMention')
            ->andWhere('mmc.niveau = :idNiveau')
            ->andWhere('mmc.deletedAt IS NULL')
            ->setParameter('idMatiere', $idMatiere)
            ->setParameter('idMention', $idMention)
            ->setParameter('idNiveau', $idNiveau)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMentionAndSemestre(int $idMention, int $idSemestre): array
    {
        return $this->createQueryBuilder('mmc')
            ->join('mmc.matiere', 'mat')
            ->andWhere('mmc.mention = :idMention')
            ->andWhere('mat.semestre = :idSemestre')
            ->andWhere('mmc.deletedAt IS NULL')
            ->setParameter('idMention', $idMention)
            ->setParameter('idSemestre', $idSemestre)
            ->orderBy('mat.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
