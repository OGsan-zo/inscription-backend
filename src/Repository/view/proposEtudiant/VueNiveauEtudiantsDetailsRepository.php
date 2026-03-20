<?php

namespace App\Repository\view\proposEtudiant;

use App\Entity\view\proposEtudiant\VueNiveauEtudiantsDetails;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueNiveauEtudiantsDetailsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueNiveauEtudiantsDetails::class);
    }
    
}