<?php

namespace App\Entity;

use App\Entity\proposEtudiant\Mentions;
use App\Entity\utils\BaseEntite;
use App\Repository\notes\MatiereMentionCoefficientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereMentionCoefficientRepository::class)]
#[ORM\HasLifecycleCallbacks]
class MatiereMentionCoefficient extends BaseEntite
{
    #[ORM\Column]
    private ?int $coefficient = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentions $mention = null;

    #[ORM\ManyToOne(inversedBy: 'matiereMentionCoefficients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matieres $matiere = null;

    /**
     * @var Collection<int, Notes>
     */
    #[ORM\OneToMany(targetEntity: Notes::class, mappedBy: 'matiereMentionCoefficient')]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): static
    {
        $this->coefficient = $coefficient;
        return $this;
    }

    public function getMention(): ?Mentions
    {
        return $this->mention;
    }

    public function setMention(?Mentions $mention): static
    {
        $this->mention = $mention;
        return $this;
    }

    public function getMatiere(): ?Matieres
    {
        return $this->matiere;
    }

    public function setMatiere(?Matieres $matiere): static
    {
        $this->matiere = $matiere;
        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }
}
