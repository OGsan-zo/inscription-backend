<?php

namespace App\Repository\notes;
use App\Entity\note\UE;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class UERepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UE::class);
    }

}
