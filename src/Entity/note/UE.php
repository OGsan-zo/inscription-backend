<?php

namespace App\Entity\note;

use App\Entity\utils\BaseName;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UE extends BaseName
{
}
