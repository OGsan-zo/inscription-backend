<?php

namespace App\Repository\view\note;

use App\Entity\view\note\VueDernierNotes;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueDernierNotesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueDernierNotes::class);
    }
    
}