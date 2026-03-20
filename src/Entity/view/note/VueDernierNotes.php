<?php

namespace App\Entity\view\note;

use App\Entity\utils\BaseValidation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'vue_dernieres_notes')]
class VueDernierNotes extends BaseValidation
{
    #[ORM\Column]
    private ?int $etudiantId = null;

    #[ORM\Column]
    private ?int $matiereMentionCoefficientId = null;

    #[ORM\Column]
    private ?int $typeNoteId = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $valeur = null;


    #[ORM\Column]
    private ?int $annee = null;

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