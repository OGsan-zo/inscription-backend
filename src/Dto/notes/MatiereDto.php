<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class MatiereDto
{
    #[Assert\NotBlank(message: 'Le nom de la matière est obligatoire.')]
    #[Assert\Length(max: 150, maxMessage: 'Le nom ne peut pas dépasser 150 caractères.')]
    public ?string $nom = null;
}
