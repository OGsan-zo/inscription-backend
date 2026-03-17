<?php

namespace App\Entity;

use App\Entity\utils\BaseEntite;
use App\Repository\parcours\ParcoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Parcours extends BaseEntite
{
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'mention')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentions $mention = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveaux $niveau = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getNiveau(): ?Niveaux
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveaux $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }
}
