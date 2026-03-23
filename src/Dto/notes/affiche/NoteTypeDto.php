<?php

namespace App\Dto\notes\affiche;

class NoteTypeDto
{
    public string $type;
   
    public array $notesListes;

    public float $moyenne;   
    
    public function getType(): string
    {
        return $this->type;
    }
    
    public function setType(string $type): void
    {
        $this->type = $type;
    }
    
    public function getNotesListes(): array
    {
        return $this->notesListes;
    }
    
    public function setNotesListes(array $notesListes): void
    {
        $this->notesListes = $notesListes;
    }
    
    public function ajouterNoteListe(NoteListeDto $noteListe): void
    {
        $this->notesListes[] = $noteListe;
    }
    
    public function getMoyenne(): float
    {
        return $this->moyenne;
    }
    
    public function setMoyenne(float $moyenne): void
    {
        $this->moyenne = $moyenne;
    }
}
