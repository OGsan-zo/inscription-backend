<?php

namespace App\Entity\view\proposEtudiant;

use App\Entity\utils\BaseEntite;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'vue_utilisateur_role')]
class VueUtilisateurRoles extends BaseEntite
{
 
    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private string $prenom;

    #[ORM\Column(name: 'role_id', type: 'integer')]
    private int $roleId;

    #[ORM\Column(name: 'role_nom', type: 'string', length: 255)]
    private string $roleNom;

    // ============================
    // GETTERS
    // ============================

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getRoleNom(): string
    {
        return $this->roleNom;
    }
}