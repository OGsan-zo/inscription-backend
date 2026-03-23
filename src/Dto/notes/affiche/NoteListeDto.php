<?php

namespace App\Dto\notes\affiche;

class NoteListeDto
{
    public string $ue;
    public array $notes;
    public int $sommeCoefficients;

    public int $sommeNotesAvecCoefficient;

    public float $moyenne;

    public bool $isValid;

    public function getUe(): string
    {
        return $this->ue;
    }
    
    public function setUe(string $ue): void
    {
        $this->ue = $ue;
    }
    
    public function getNotes(): array
    {
        return $this->notes;
    }
    
    public function setNotes(array $notes): void
    {
        $this->notes = $notes;
    }
    public function ajouterNote(NoteAfficheDto $note): void
    {
        $this->notes[] = $note;
    }
    
    public function getIsValid(): bool
    {
        return $this->isValid;
    }
    
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }
    
    public function getSommeCoefficients(): int
    {
        return $this->sommeCoefficients;
    }
    
    public function setSommeCoefficients(int $sommeCoefficients): void
    {
        $this->sommeCoefficients = $sommeCoefficients;
    }
    
    public function calculerSommeCoefficientsNotes(): void
    {
        $this->sommeCoefficients = 0;
        $this->sommeNotesAvecCoefficient = 0;
        foreach ($this->notes as $note) {
            $this->sommeCoefficients += $note->getCoefficient();
            $this->sommeNotesAvecCoefficient += $note->getNote() * $note->getCoefficient();
        }
    }
    
    public function getSommeNotesAvecCoefficient(): int
    {
        return $this->sommeNotesAvecCoefficient;
    }
    
    public function setSommeNotesAvecCoefficient(int $sommeNotesAvecCoefficient): void
    {
        $this->sommeNotesAvecCoefficient = $sommeNotesAvecCoefficient;
    }

    public function getMoyenne(): float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): void
    {
        $this->moyenne = $moyenne;
    }
    
    public function calculerMoyenne(): void
    {
        if ($this->sommeCoefficients === 0) {
            $this->moyenne = 0;
            return;
        }
        $this->moyenne = $this->sommeNotesAvecCoefficient / $this->sommeCoefficients;
    }
}
