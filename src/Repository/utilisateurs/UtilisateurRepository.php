<?php

namespace App\Repository\utilisateurs;

use App\Entity\utilisateurs\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    //    /**
    //     * @return Utilisateur[] Returns an array of Utilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Utilisateur
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function login(string $email, string $plainPassword): ?Utilisateur
    {
        $user = $this->findOneBy(['email' => $email]);

        if ($user && password_verify($plainPassword, $user->getMdp())) {
            return $user;
        }

        return null; 
    }
    public function getAllParOrdre(): array
    {
           return $this->createQueryBuilder('u')
               
               ->orderBy('u.dateCreation', 'ASC')
               ->getQuery()
               ->getResult()
           ;
    }
    public function findAllIdsParRole(array $ids): array
    {
        $qb = $this->createQueryBuilder('u')
            ->join('u.role', 'r');

        if (!empty($ids)) {
            $qb->andWhere($qb->expr()->in('r.id', ':ids'))
            ->setParameter('ids', $ids);
        }

        return $qb->orderBy('u.id', 'ASC')
                ->getQuery()
                ->getResult();
    }
    
    

}
