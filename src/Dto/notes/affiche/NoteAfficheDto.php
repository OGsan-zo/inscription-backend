<?php

namespace App\Dto\notes\affiche;

class NoteAfficheDto
{
    private string $matiere = '';
    private int $coefficient = 0;

    private int $credit = 0;

    private ?float $note = null; // ✅ nullable
    private float $noteAvecCoefficient = 0;

    public function getMatiere(): string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): void
    {
        $this->matiere = $matiere;
    }

    public function getCoefficient(): int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): void
    {
        $this->coefficient = $coefficient;
    }

    public function getCredit(): int
    {
        return $this->credit;
    }
    
    public function setCredit(int $credit): void
    {
        $this->credit = $credit;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): void
    {
        // ✅ accepte 0 et null correctement
        $this->note = $note;
    }
    
    public function getNoteAvecCoefficient(): float
    {
        return $this->noteAvecCoefficient;
    }
    public function setNoteAvecCoefficient(?float $noteAvecCoef): void
    {
        $this->noteAvecCoefficient = $noteAvecCoef ?? 0;
    }
    
    
}