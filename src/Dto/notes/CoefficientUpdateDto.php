<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class CoefficientUpdateDto
{
    #[Assert\NotNull(message: "Le coefficient est obligatoire.")]
    #[Assert\Positive(message: "Le coefficient doit être positif.")]
    public ?int $coefficient = null;
}
