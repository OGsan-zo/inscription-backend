<?php

namespace App\Repository\view\proposEtudiant;

use App\Entity\view\proposEtudiant\VueUtilisateurRoles;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueUtilisateurRolesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueUtilisateurRoles::class);
    }
    
}