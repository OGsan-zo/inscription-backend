<?php

namespace App\Service\proposEtudiant\view;

use App\Repository\view\proposEtudiant\VueUtilisateurRolesRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueUtilisateurRolesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueUtilisateurRolesRepository $vueUtilisateurRolesRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueUtilisateurRolesRepository
    {
        return $this->vueUtilisateurRolesRepository;
    }
    public function grouperParRole(array $utilisateurs): array
    {
        $resultat = [];

        foreach ($utilisateurs as $user) {
            $role = $user->getRoleNom();

            if (!isset($resultat[$role])) {
                $resultat[$role] = [];
            }

            $resultat[$role][] = $user;
        }

        return $resultat;
    }
    public function getAllRegrouperArray(): array
    {
        $valiny = [];
        $excludes = ['createdAt', 'deletedAt', 'roleId'];

        $utilisateurs = $this->getAll();

        // tableau groupé par roleNom
        $utilisateursGrouper = $this->grouperParRole($utilisateurs);

        foreach ($utilisateursGrouper as $role => $users) {
            $valiny[$role] = [];

            foreach ($users as $user) {
                // conversion en array (via ta BaseEntite)
                $valiny[$role][] = $user->toArray($excludes);
            }
        }

        return $valiny;
    }

  
}
