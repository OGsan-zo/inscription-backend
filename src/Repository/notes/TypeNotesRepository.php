<?php

namespace App\Repository\notes;
use App\Entity\note\TypeNotes;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class TypeNotesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeNotes::class);
    }

}
