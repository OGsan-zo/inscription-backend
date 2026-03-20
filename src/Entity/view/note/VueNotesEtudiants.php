<?php

namespace App\Entity\view\note;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\utils\BaseValidation;

#[ORM\Entity]
#[ORM\Table(name: "vue_notes_etudiants")]
class VueNotesEtudiants extends BaseValidation
{

    #[ORM\Column(type: "decimal", precision: 5, scale: 2, nullable: true)]
    private ?string $valeur = null;

    #[ORM\Column(type: "integer")]
    private int $typeNoteId;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $typeNoteName = null;

    #[ORM\Column(type: "integer")]
    private int $matiereMentionCoefficientId;

    #[ORM\Column(type: "integer")]
    private int $etudiantId;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $prenom = null;
    
    #[ORM\Column(type: "integer")]
    private int $annee;

    // 👉 getters seulement (important pour une VIEW)


    public function getValeur(): ?string { return $this->valeur; }
    public function getTypeNoteId(): int { return $this->typeNoteId; }
    public function getTypeNoteName(): ?string { return $this->typeNoteName; }
    public function getMatiereMentionCoefficientId(): int { return $this->matiereMentionCoefficientId; }
    public function getEtudiantId(): int { return $this->etudiantId; }
    public function getNom(): ?string { return $this->nom; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function getAnnee(): int { return $this->annee; }
}


