<?php

namespace App\Dto\notes\view;

use App\Entity\view\proposEtudiant\VueNiveauEtudiantsDetails;

class NiveauEtudiantDetailsDto
{
    public VueNiveauEtudiantsDetails $details;
    public array $notes = [];

    public function toArray(array $exclude = []): array
    {
        $notes = array_map(function ($note) use ($exclude) {
            return $note->toArray($exclude);
        }, $this->notes);
        return [
            'details' => $this->details->toArray($exclude),
            'notes' => $notes,
        ];
    }
}
