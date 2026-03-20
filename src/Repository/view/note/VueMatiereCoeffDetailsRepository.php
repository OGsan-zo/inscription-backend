<?php

namespace App\Repository\view\note;

use App\Entity\view\note\VueMatiereCoeffDetails;
use App\Repository\utils\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class VueMatiereCoeffDetailsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VueMatiereCoeffDetails::class);
    }
    
}