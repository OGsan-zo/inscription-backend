<?php

namespace App\Service\proposEtudiant;
use App\Repository\NiveauxRepository;
use App\Entity\Niveaux;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class NiveauService
{   private $niveauxRepository;
    private EntityManagerInterface $em;

    public function __construct(
        NiveauxRepository $niveauxRepository,
        private readonly ValidationService $validationService,
    ) {
        $this->niveauxRepository = $niveauxRepository;
    }
    
    public function insertNiveau(Niveaux $niveau): Niveaux
    {
        $this->em->persist($niveau);
        $this->em->flush();
        return $niveau;
    }
    public function getNiveauSuivant(Niveaux $niveauActuel): ?Niveaux
    {
        $niveauSuivant = $this->niveauxRepository->getNiveauSuivant($niveauActuel);
        return $niveauSuivant;
    }
    public function getById($id): ?Niveaux
    {
        return $this->niveauxRepository->find($id);
    }

    public function getVerifiedNiveau(int $id): Niveaux
    {
        $niveau = $this->niveauxRepository->find($id);
        $this->validationService->throwIfNull($niveau, "Niveau introuvable pour l'ID $id.");
        return $niveau;
    }
    public function getAllNiveaux(): array
    {
        return $this->niveauxRepository->findAll();
    }
    public function toArray(?Niveaux $niveau ): array
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
