<?php

namespace App\Dto\notes;


class MatiereCoefficientDetailDto
{
    public string $ue;

    public array $matiereCoefficients = [];

    public function getUe(): string
    {
        return $this->ue;
    }
    public function setUe(string $ue): void
    {
        $this->ue = $ue;
    }
    public function getMatiereCoefficients(): array
    {
        return $this->matiereCoefficients;
    }
    public function setMatiereCoefficients(array $matiereCoefficients): void
    {
        $this->matiereCoefficients = $matiereCoefficients;
    }
    public function ajouterMatiere(MatiereCoefficientDetailDto $mcd): void
    {
        $this->matiereCoefficients[] = $mcd;
    }
    
}