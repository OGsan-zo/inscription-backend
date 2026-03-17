<?php

namespace App\Repository\parcours;

use App\Entity\Parcours;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class ParcoursRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcours::class);
    }
}
