<?php

namespace App\Entity\view\note;

use App\Entity\utils\BaseEntite;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "vue_matiere_coeff_detail")]
class VueMatiereCoeffDetails extends BaseEntite
{

    #[ORM\Column(type: "integer")]
    private int $coefficient;
    
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $ue = null;
    
    #[ORM\Column(type: "integer")]
    private ?int $matiereId;
    

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $matiereNom = null;
    
    #[ORM\Column(type: "integer")]
    private ?int $semestreId;
    
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $semestreNom = null;
    

    #[ORM\Column(type: "integer")]
    private ?int $mentionId;
    
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $mentionNom = null;
    
    #[ORM\Column(type: "integer")]
    private ?int $niveauId;
    
    #[ORM\Column(type: "string", nullable: true)]
    private ?string $niveauNom = null;

    #[ORM\Column(type: "integer")]
    private int $professeurId;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $professeurNom = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $professeurPrenom = null;


    // ===== GETTERS =====

    public function getId(): int { return $this->id; }
    public function getCoefficient(): int { return $this->coefficient; }
    public function getUe(): ?string { return $this->ue; }
    public function getMatiereId(): ?int { return $this->matiereId; }
    public function getMatiereNom(): ?string { return $this->matiereNom; }
    public function getSemestreId(): ?int { return $this->semestreId; }
    public function getSemestreNom(): ?string { return $this->semestreNom; }
    public function getMentionId(): ?int { return $this->mentionId; }
    public function getMentionNom(): ?string { return $this->mentionNom; }
    public function getNiveauId(): ?int { return $this->niveauId; }
    public function getNiveauNom(): ?string { return $this->niveauNom; }
    public function getProfesseurId(): int { return $this->professeurId; }
    public function getProfesseurNom(): ?string { return $this->professeurNom; }
    public function getProfesseurPrenom(): ?string { return $this->professeurPrenom; }
  }