<?php

namespace App\Repository\view\note;

use App\Entity\view\note\VueMatiereCoeffDetail;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueMatiereCoeffDetailRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueMatiereCoeffDetail::class);
    }
    
}