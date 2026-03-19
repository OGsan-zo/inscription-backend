<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class UEDto
{
    #[Assert\NotBlank(message: 'Le name est obligatoire.')]
    #[Assert\Length(max: 150, maxMessage: 'Le name ne peut pas dépasser 150 caractères.')]
    public ?string $name = null;
}