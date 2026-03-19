<?php

namespace App\Entity\proposEtudiant;

use App\Entity\note\MatiereMentionCoefficient;
use App\Entity\payment\Payments;
use App\Repository\proposEtudiant\NiveauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauxRepository::class)]
class Niveaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    /**
     * @var Collection<int, NiveauEtudiants>
     */
    #[ORM\OneToMany(targetEntity: NiveauEtudiants::class, mappedBy: 'niveau')]
    private Collection $niveaux;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $grade = null;

    /**
     * @var Collection<int, Payments>
     */
    #[ORM\OneToMany(targetEntity: Payments::class, mappedBy: 'niveau')]
    private Collection $payments;

    /**
     * @var Collection<int, Parcours>
     */
    #[ORM\OneToMany(targetEntity: Parcours::class, mappedBy: 'niveau')]
    private Collection $parcours;

    /**
     * @var Collection<int, MatiereMentionCoefficient>
     */
    #[ORM\OneToMany(targetEntity: MatiereMentionCoefficient::class, mappedBy: 'niveau')]
    private Collection $matiereMentionCoefficients;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->parcours = new ArrayCollection();
        $this->matiereMentionCoefficients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, NiveauEtudiants>
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(NiveauEtudiants $niveau): static
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux->add($niveau);
            $niveau->setNiveau($this);
        }

        return $this;
    }

    public function removeNiveau(NiveauEtudiants $niveau): static
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getNiveau() === $this) {
                $niveau->setNiveau(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * @return Collection<int, Payments>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payments $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setNiveau($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Parcours>
     */
    public function getParcours(): Collection
    {
        return $this->parcours;
    }

    public function addParcours(Parcours $parcours): static
    {
        if (!$this->parcours->contains($parcours)) {
            $this->parcours->add($parcours);
            $parcours->setNiveau($this);
        }

        return $this;
    }

    public function removeParcours(Parcours $parcours): static
    {
        if ($this->parcours->removeElement($parcours)) {
            if ($parcours->getNiveau() === $this) {
                $parcours->setNiveau(null);
            }
        }

        return $this;
    }

    public function removePayment(Payments $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getNiveau() === $this) {
                $payment->setNiveau(null);
            }
        }

        return $this;
    }
    public function getMatiereMentionCoefficients(): Collection
    {
        return $this->matiereMentionCoefficients;
    }
    public function addMatiereMentionCoefficient(MatiereMentionCoefficient $matiereMentionCoefficient): static
    {
        if (!$this->matiereMentionCoefficients->contains($matiereMentionCoefficient)) {
            $this->matiereMentionCoefficients->add($matiereMentionCoefficient);
            $matiereMentionCoefficient->setNiveau($this);
        }

        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'type' => $this->type,
            'grade' => $this->grade,
        ];
    }
}
