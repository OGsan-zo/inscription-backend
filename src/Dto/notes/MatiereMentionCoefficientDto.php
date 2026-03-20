<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class MatiereMentionCoefficientDto
{
    #[Assert\NotNull(message: "L'idMatiere est obligatoire.")]
    #[Assert\Positive(message: "L'idMatiere doit être positif.")]
    public ?int $idMatiere = null;

    #[Assert\NotNull(message: "L'idMention est obligatoire.")]
    #[Assert\Positive(message: "L'idMention doit être positif.")]
    public ?int $idMention = null;

    #[Assert\NotNull(message: "Le coefficient est obligatoire.")]
    #[Assert\Positive(message: "Le coefficient doit être positif.")]
    public ?int $coefficient = null;
    
    #[Assert\NotNull(message: "L'idNiveau est obligatoire.")]
    #[Assert\Positive(message: "L'idNiveau doit être positif.")]
    public ?int $idNiveau = null;

    #[Assert\NotNull(message: "L'idProfesseur est obligatoire.")]
    #[Assert\Positive(message: "L'idProfesseur doit être positif.")]
    public ?int $idProfesseur = null;
}
