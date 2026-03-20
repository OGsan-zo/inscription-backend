<?php

namespace App\Entity\view\proposEtudiant;

use App\Entity\utils\BaseEntite;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: "vue_niveau_etudiants_details")]
class VueNiveauEtudiantsDetails extends BaseEntite
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $etudiantId;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: "integer")]
    private int $niveauId;

    #[ORM\Column(type: "integer")]
    private int $mentionId;


    #[ORM\Column(type: "integer")]
    private int $annee;

    // ===== GETTERS =====

    public function getEtudiantId(): int
    {
        return $this->etudiantId;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getNiveauId(): int
    {
        return $this->niveauId;
    }

    public function getMentionId(): int
    {
        return $this->mentionId;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }
}