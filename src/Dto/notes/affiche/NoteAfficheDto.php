<?php

namespace App\Dto\notes\affiche;


class NoteAfficheDto
{
    public string $matiere;
    public int $coefficient;
    public float $note;

    public float $noteAvecCoefficient;

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

    public function getNote(): float
    {
        return $this->note;
    }
    public function setNote(float $note): void
    {
        $this->note = $note;
    }
    
    public function getNoteAvecCoefficient(): float
    {
        return $this->noteAvecCoefficient;
    }
    public function setNoteAvecCoefficient(float $noteAvecCoefficient): void
    {
        $this->noteAvecCoefficient = $noteAvecCoefficient;
    }
    
}