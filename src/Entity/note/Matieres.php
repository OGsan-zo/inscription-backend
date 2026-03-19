<?php

namespace App\Entity\note;

use App\Entity\utils\BaseEntite;
use App\Repository\notes\MatieresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatieresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Matieres extends BaseEntite
{
    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Semestres $semestre = null;

    /**
     * @var Collection<int, MatiereMentionCoefficient>
     */
    #[ORM\OneToMany(targetEntity: MatiereMentionCoefficient::class, mappedBy: 'matiere')]
    private Collection $matiereMentionCoefficients;

    public function __construct()
    {
        $this->matiereMentionCoefficients = new ArrayCollection();
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
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

    /**
     * @return Collection<int, MatiereMentionCoefficient>
     */
    public function getMatiereMentionCoefficients(): Collection
    {
        return $this->matiereMentionCoefficients;
    }
}
