<?php

namespace App\Dto\notes;

use Symfony\Component\Validator\Constraints as Assert;

class NoteInsetionListeDto
{
    #[Assert\NotNull(message: "L'idMatiereCoefficient est obligatoire.")]
    #[Assert\Positive(message: "L'idMatiereCoefficient doit être positif.")]
    public ?int $idMatiereCoefficient = null;
    
    #[Assert\NotNull(message: "L'annee est obligatoire.")]
    #[Assert\Positive(message: "L'annee doit être positive.")]
    public ?int $annee = null;


    #[Assert\NotNull(message: "La liste des étudiants est obligatoire.")]
    #[Assert\Count(min: 1, minMessage: "Au moins un étudiant est requis.")]
    public ?array $listeEtudiants = null;
    #[Assert\NotNull(message: "Le champ isNormale est obligatoire.")]
    public ?bool $isNormale = null;

}
