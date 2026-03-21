<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class NoteDto
{
    #[Assert\NotNull(message: 'Le etudiantId est obligatoire.')]
    #[Assert\Type('integer', message: 'Le etudiantId doit être un ID valide.')]
    public ?int $etudiantId = null;

    #[Assert\NotNull(message: "La valeur de la note est obligatoire.")]
    #[Assert\PositiveOrZero(message: "La note doit être positive ou zéro.")]
    #[Assert\Range(max: 20, maxMessage: "La note ne peut pas dépasser 20.")]
    public ?float $valeur = null;
}
