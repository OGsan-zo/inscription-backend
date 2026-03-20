<?php

namespace App\Repository\view;

use App\Entity\view\VueNotesEtudiants;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueNotesEtudiantsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueNotesEtudiants::class);
    }
    
}