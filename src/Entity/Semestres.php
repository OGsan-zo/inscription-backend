<?php

namespace App\Entity;

use App\Entity\utils\BaseEntite;
use App\Repository\notes\SemestresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemestresRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Semestres extends BaseEntite
{
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $grade = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveaux $niveau = null;

    /**
     * @var Collection<int, Matieres>
     */
    #[ORM\OneToMany(targetEntity: Matieres::class, mappedBy: 'semestre')]
    private Collection $matieres;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
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

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): static
    {
        $this->grade = $grade;
        return $this;
    }

    public function getNiveau(): ?Niveaux
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveaux $niveau): static
    {
        $this->niveau = $niveau;
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
