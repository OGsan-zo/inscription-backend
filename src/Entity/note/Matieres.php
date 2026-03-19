<?php

namespace App\Entity\note;

use App\Entity\utils\BaseName;
use App\Repository\notes\MatieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Matieres extends BaseName
{
    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semestres $semestre = null;
    
    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $ue = null;

    /**
     * @var Collection<int, MatiereMentionCoefficient>
     */
    #[ORM\OneToMany(targetEntity: MatiereMentionCoefficient::class, mappedBy: 'matiere')]
    private Collection $matiereMentionCoefficients;

    public function __construct()
    {
        $this->matiereMentionCoefficients = new ArrayCollection();
    }
    public function getSemestre(): ?Semestres
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestres $semestre): static
    {
        $this->semestre = $semestre;
        return $this;
    }

    public function getUe(): ?UE
    {
        return $this->ue;
    }

    public function setUe(?UE $ue): static
    {
        $this->ue = $ue;
        return $this;
    }

    /**
     * @return Collection<int, MatiereMentionCoefficient>
     */
    public function getMatiereMentionCoefficients(): Collection
    {
        return $this->matiereMentionCoefficients;
    }
}
