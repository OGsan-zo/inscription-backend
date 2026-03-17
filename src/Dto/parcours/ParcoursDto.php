<?php

namespace App\Dto\parcours;

use Symfony\Component\Validator\Constraints as Assert;

class ParcoursDto
{
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    public ?string $nom = null;

    #[Assert\NotNull(message: "L'ID de la mention est obligatoire.")]
    #[Assert\Positive(message: "L'ID de la mention doit être positif.")]
    public ?int $idMention = null;

    #[Assert\NotNull(message: "L'ID du niveau est obligatoire.")]
    #[Assert\Positive(message: "L'ID du niveau doit être positif.")]
    public ?int $idNiveau = null;
}
