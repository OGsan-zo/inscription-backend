<?php

namespace App\Entity\view\note;

use App\Entity\utils\BaseValidation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass] // 🔥 IMPORTANT
abstract class BaseVueNote extends BaseValidation
{
    #[ORM\Column]
    protected ?int $etudiantId = null;

    #[ORM\Column]
    protected ?int $matiereMentionCoefficientId = null;

    #[ORM\Column]
    protected ?int $typeNoteId = null;

    #[ORM\Column(type: 'float', nullable: true)]
    protected ?float $valeur = null;

    #[ORM\Column]
    protected ?int $annee = null;

    // -------------------
    // Getters
    // -------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiantId(): ?int
    {
        return $this->etudiantId;
    }

    public function getMatiereMentionCoefficientId(): ?int
    {
        return $this->matiereMentionCoefficientId;
    }

    public function getTypeNoteId(): ?int
    {
        return $this->typeNoteId;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }
}