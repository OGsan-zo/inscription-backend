<?php

namespace App\Entity\note;

use App\Entity\proposEtudiant\Etudiants;
use App\Entity\utils\BaseValidation;
use App\Repository\notes\NotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Notes extends BaseValidation
{
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $valeur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiants $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MatiereMentionCoefficient $matiereMentionCoefficient = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeNotes $typeNote = null;

    #[ORM\Column(type: "integer")]
    private ?int $annee = null;


    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(?string $valeur): static
    {
        if ($valeur === null) {
            $this->valeur = null;
            return $this;
        }

        // Convertir en float
        $val = (float) $valeur;

        // Vérification intervalle
        if ($val < 0 || $val > 20) {
            throw new \InvalidArgumentException("La note doit être comprise entre 0 et 20.");
        }

        $this->valeur = $val;

        return $this;
    }

    public function getEtudiant(): ?Etudiants
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiants $etudiant): static
    {
        $this->etudiant = $etudiant;
        return $this;
    }

    public function getMatiereMentionCoefficient(): ?MatiereMentionCoefficient
    {
        return $this->matiereMentionCoefficient;
    }

    public function setMatiereMentionCoefficient(?MatiereMentionCoefficient $matiereMentionCoefficient): static
    {
        $this->matiereMentionCoefficient = $matiereMentionCoefficient;
        return $this;
    }

    public function getTypeNote(): ?TypeNotes
    {
        return $this->typeNote;
    }

    public function setTypeNote(?TypeNotes $typeNote): static
    {
        $this->typeNote = $typeNote;
        return $this;
    }
    public function getAnnee(): ?int
    {
        return $this->annee;
    }
    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;
        return $this;
    }
}
