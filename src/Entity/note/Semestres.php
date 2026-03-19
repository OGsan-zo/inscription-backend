<?php

namespace App\Entity\note;

use App\Entity\utils\BaseName;
use App\Repository\notes\SemestresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemestresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Semestres extends BaseName
{
    #[ORM\Column(type: "integer", nullable: false)]
    private ?int $grade = null;

    /**
     * @var Collection<int, Matieres>
     */
    #[ORM\OneToMany(targetEntity: Matieres::class, mappedBy: 'semestre')]
    private Collection $matieres;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(?int $grade): static
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return Collection<int, Matieres>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }
}