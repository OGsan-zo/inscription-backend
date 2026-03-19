<?php

namespace App\Service\payment;
use App\Entity\payment\TypeDroits;
use App\Repository\payment\TypeDroitsRepository;
use Exception;

class TypeDroitService
{
    private $typeDroitsRepository;

    public function __construct(TypeDroitsRepository $typeDroitsRepository)
    {
        $this->typeDroitsRepository = $typeDroitsRepository;

    }
    // 1 pedagogique
    // 2 administratif
    public function getById($id): ?TypeDroits
    {
        return $this->typeDroitsRepository->find($id);
    }

    /**
     * Retourne l'ID du type de droit à partir de son libellé (version simplifiée).
     * @throws Exception
     */
    public function getIdByLabel(string $label): int
    {
        return match ($label) {
            'Pédagogique' => 1,
            'Administratif' => 2,
            'Ecolage' => 3,
            default => throw new Exception("Type de droit inconnu : $label")
        };
    }
}
