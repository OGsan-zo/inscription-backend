<?php

namespace App\Repository\notes;

use App\Entity\MatiereMentionCoefficient;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatiereMentionCoefficientRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatiereMentionCoefficient::class);
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
