<?php

namespace App\Repository\notes;

use App\Entity\note\Notes;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    /**
     * Retourne les notes d'un étudiant filtrées par mention et semestre
     */
    // public function findByEtudiantMentionSemestre(int $idEtudiant, int $idMention, int $idSemestre): array
    // {
    //     return $this->createQueryBuilder('n')
    //         ->join('n.matiereMentionCoefficient', 'mmc')
    //         ->join('mmc.matiere', 'mat')
    //         ->join('mat.semestre', 's')
    //         ->andWhere('n.etudiant = :idEtudiant')
    //         ->andWhere('mmc.mention = :idMention')
    //         ->andWhere('s.id = :idSemestre')
    //         ->andWhere('n.deletedAt IS NULL')
    //         ->setParameter('idEtudiant', $idEtudiant)
    //         ->setParameter('idMention', $idMention)
    //         ->setParameter('idSemestre', $idSemestre)
    //         ->orderBy('mat.nom', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findByEtudiantAndMMC(int $idEtudiant, int $idMMC): ?Notes
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.etudiant = :idEtudiant')
            ->andWhere('n.matiereMentionCoefficient = :idMMC')
            ->andWhere('n.deletedAt IS NULL')
            ->setParameter('idEtudiant', $idEtudiant)
            ->setParameter('idMMC', $idMMC)
            ->getQuery()
            ->getOneOrNullResult();
    }



    
    /**
     * Retourne toutes les notes d'un étudiant
     */
    public function findByEtudiant(int $idEtudiant): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.etudiant = :idEtudiant')
            ->andWhere('n.deletedAt IS NULL')
            ->setParameter('idEtudiant', $idEtudiant)
            ->getQuery()
            ->getResult();
    }
}
