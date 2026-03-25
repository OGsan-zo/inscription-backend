<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class MatiereDto
{


    #[Assert\NotBlank(message: 'Le name est obligatoire.')]
    #[Assert\Length(max: 150, maxMessage: 'Le name ne peut pas dépasser 150 caractères.')]
    public ?string $name = null;

    #[Assert\NotNull(message: 'Le semestreId est obligatoire.')]
    #[Assert\Type('integer', message: 'Le semestreId doit être un ID valide.')]
    public ?int $semestreId = null;

    #[Assert\NotNull(message: "L'ueId est obligatoire.")]
    #[Assert\Type('integer', message: "L'ueId doit être un ID valide.")]
    public ?int $ueId = null;
}