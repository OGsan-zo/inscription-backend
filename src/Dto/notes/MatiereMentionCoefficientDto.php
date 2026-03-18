<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class MatiereMentionCoefficientDto
{
    #[Assert\NotNull(message: "L'ID de la matière est obligatoire.")]
    #[Assert\Positive(message: "L'ID de la matière doit être positif.")]
    public ?int $idMatiere = null;

    #[Assert\NotNull(message: "L'ID de la mention est obligatoire.")]
    #[Assert\Positive(message: "L'ID de la mention doit être positif.")]
    public ?int $idMention = null;

    #[Assert\NotNull(message: "Le coefficient est obligatoire.")]
    #[Assert\Positive(message: "Le coefficient doit être positif.")]
    public ?int $coefficient = null;
}
