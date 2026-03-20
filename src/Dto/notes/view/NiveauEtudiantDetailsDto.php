<?php

namespace App\Dto\notes\view;

use App\Entity\view\proposEtudiant\VueNiveauEtudiantsDetails;

class NiveauEtudiantDetailsDto
{
    public VueNiveauEtudiantsDetails $details;
    public array $notes = [];
}
