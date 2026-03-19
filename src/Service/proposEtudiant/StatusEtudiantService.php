<?php

namespace App\Service\proposEtudiant;
use App\Entity\proposEtudiant\StatusEtudiants;

use App\Repository\proposEtudiant\StatusEtudiantsRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusEtudiantService
{   private $statusEtudiantRepository;
    private EntityManagerInterface $em;

    public function __construct(StatusEtudiantsRepository $statusEtudiantRepository)
    {
        $this->statusEtudiantRepository = $statusEtudiantRepository;

    }
    
    public function insertStatusEtudiant(StatusEtudiants $statusEtudiant): StatusEtudiants
    {           
        $this->em->persist($statusEtudiant);
        $this->em->flush();
        return $statusEtudiant;
    }
   
    public function getById($id): ?StatusEtudiants
    {
        return $this->statusEtudiantRepository->find($id);
    }
    public function getAllNiveaux(): array
    {
        return $this->statusEtudiantRepository->findAll();
    }
    // public function toArray(?Niveaux $niveau ): array
    // {
    //     if ($niveau === null) {
    //         return [];
    //     }
    //     return [
    //         'id'    => $niveau->getId(),
    //         'nom'   => $niveau->getNom(),
    //         'type'  => $niveau->getType(),
    //         'grade' => $niveau->getGrade(),
    //     ];
    // }
    
}
