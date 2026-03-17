<?php

namespace App\Dto\parcours;

use Symfony\Component\Validator\Constraints as Assert;

class AssignerParcoursDto
{
    #[Assert\NotNull(message: "L'ID du parcours est obligatoire.")]
    #[Assert\Positive(message: "L'ID du parcours doit être positif.")]
    public ?int $idParcours = null;

    #[Assert\NotNull(message: "La liste des étudiants est obligatoire.")]
    #[Assert\Count(min: 1, minMessage: "Au moins un étudiant est requis.")]
    public ?array $idNiveauEtudiants = null;
}
