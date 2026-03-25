<?php

namespace App\Dto\notes\affiche;

class NoteListeDto
{
    private string $ue = '';
    private array $notes = []; // ✅ initialisé

    private int $sommeCoefficients = 0; // ✅ initialisé
    private float $sommeNotesAvecCoefficient = 0; // ✅ float + initialisé
    private int $sommeCredit = 0;

    private int $sommeCreditValide = 0;

    private float $moyenne = 0; // ✅ initialisé
    private bool $isValid = false; // valeur par défaut

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
        $this->notes = $notes ?? []; // sécurité
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
    
    public function getSommeNotesAvecCoefficient(): float
    {
        return $this->sommeNotesAvecCoefficient;
    }

    public function getMoyenne(): float
    {
        return $this->moyenne;
    }

    /**
     * ✅ Méthode sécurisée
     */
    public function calculerSommeCoefficientsNotes(bool $isCalculerParCredit = false): void
    {
        $this->sommeCoefficients = 0;
        $this->sommeNotesAvecCoefficient = 0;
        $this->sommeCredit = 0;
        $this->sommeCreditValide = 0;
        $this->isValid = true;

        $nandalo = 0;

        foreach ($this->notes as $note) {

            $valeurNote = $note->getNote() ?? 0;
            $coefficient = $note->getCoefficient() ?? 0;
            $credit = $note->getCredit() ?? 0;

            // 🔥 choix dynamique du poids
            $poids = $isCalculerParCredit ? $credit : $coefficient;

            // ❌ note éliminatoire
            if ($valeurNote < 6) {
                $this->isValid = false;
                $nandalo++;
            }

            $this->sommeCoefficients += $poids;
            $this->sommeNotesAvecCoefficient += $valeurNote * $poids;

            // toujours utile pour validation
            $this->sommeCredit += $credit;
        }

        // ✅ moyenne pondérée
        $this->moyenne = $this->sommeCoefficients > 0
            ? $this->sommeNotesAvecCoefficient / $this->sommeCoefficients
            : 0;

        // ✅ validation UE
        if ($nandalo === 0 && $this->moyenne >= 10) {
            $this->isValid = true;
        }

        // ✅ crédits validés
        if ($this->isValid) {
            $this->sommeCreditValide = $this->sommeCredit;
        }
    }

    public function getSommeCredit(): int
    {
        return $this->sommeCredit;
    }
    
    public function setSommeCredit(int $sommeCredit): void
    {
        $this->sommeCredit = $sommeCredit;
    }
    public function getSommeCreditValide(): int
    {
        return $this->sommeCreditValide;
    }
    
    
}