<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class MatiereSemestreDto
{
    #[Assert\NotNull(message: "L'ID de la matière est obligatoire.")]
    #[Assert\Positive(message: "L'ID de la matière doit être positif.")]
    public ?int $idMatiere = null;

    #[Assert\NotNull(message: "L'ID du semestre est obligatoire.")]
    #[Assert\Positive(message: "L'ID du semestre doit être positif.")]
    public ?int $idSemestre = null;
}
