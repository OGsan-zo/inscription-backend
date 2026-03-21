<?php

namespace App\Repository\view\note;

use App\Entity\view\note\VueNotesMax;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueNotesMaxRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueNotesMax::class);
    }
    
}