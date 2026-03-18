<?php

namespace App\Service\proposEtudiant;
use App\Repository\NiveauxRepository;
use App\Entity\Niveaux;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\utils\BaseService;

class NiveauService extends BaseService
{
    public function __construct(
        private readonly NiveauxRepository $niveauxRepository,
        EntityManagerInterface $em,
        ValidationService $validationService,
    ) {
        parent::__construct($em, $validationService);
    }

    protected function getRepository(): NiveauxRepository
    {
        return $this->niveauxRepository;
    }

    public function getById(int $id): ?Niveaux
    {
        return $this->niveauxRepository->find($id);
    }

    public function getNiveauSuivant(Niveaux $niveauActuel): ?Niveaux
    {
        return $this->niveauxRepository->getNiveauSuivant($niveauActuel);
    }

    public function getAllNiveaux(): array
    {
        return $this->niveauxRepository->findAll();
    }

    public function toArray(?Niveaux $niveau): array
    {
        if ($niveau === null) {
            return [];
        }
        return [
            'id'    => $niveau->getId(),
            'nom'   => $niveau->getNom(),
            'type'  => $niveau->getType(),
            'grade' => $niveau->getGrade(),
        ];
    }
}
