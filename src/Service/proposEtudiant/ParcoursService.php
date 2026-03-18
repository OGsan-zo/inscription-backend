<?php

namespace App\Service\proposEtudiant;

use App\Dto\parcours\AssignerParcoursDto;
use App\Dto\parcours\ParcoursDto;
use App\Entity\proposEtudiant\Mentions;
use App\Entity\proposEtudiant\NiveauEtudiants;
use App\Entity\proposEtudiant\Niveaux;
use App\Entity\proposEtudiant\Parcours;
use App\Repository\proposEtudiant\ParcoursRepository;
use App\Service\proposEtudiant\EtudiantsService;
use App\Service\proposEtudiant\NiveauEtudiantsService;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class ParcoursService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly ParcoursRepository $parcoursRepository,
        private readonly ValidationService $validationService,
        private readonly NiveauEtudiantsService $niveauEtudiantsService,
        private readonly EtudiantsService $etudiantsService
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): ParcoursRepository
    {
        return $this->parcoursRepository;
    }

    public function getByMentionAndNiveau(int $idMention, int $idNiveau): array
    {
        return $this->parcoursRepository->findByMentionAndNiveau($idMention, $idNiveau);
    }

    private function getVerifiedMention(int $id): Mentions
    {
        $mention = $this->em->getRepository(Mentions::class)->find($id);
        $this->validationService->throwIfNull($mention, "Mention introuvable pour l'ID $id.");
        return $mention;
    }

    private function getVerifiedNiveau(int $id): Niveaux
    {
        $niveau = $this->em->getRepository(Niveaux::class)->find($id);
        $this->validationService->throwIfNull($niveau, "Niveau introuvable pour l'ID $id.");
        return $niveau;
    }

    public function getVerifiedParcours(int $id): Parcours
    {
        $parcours = $this->getById($id);
        $this->validationService->throwIfNull($parcours, "Parcours introuvable pour l'ID $id.");
        return $parcours;
    }

    private function getVerifiedEtudiant(int $id): NiveauEtudiants
    {
        $etudiant = $this->etudiantsService->getEtudiantById($id);
        $this->validationService->throwIfNull($etudiant, "Etudiant introuvable pour l'ID $id.");
        $dernierNiveaux = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($etudiant);
        $this->validationService->throwIfNull($dernierNiveaux, "NiveauEtudiant introuvable pour l'etudiant id $id.");
        return $dernierNiveaux;
    }

    public function saveDto(ParcoursDto $dto , Mentions $mention, Niveaux $niveau): Parcours
    {
        $parcours = new Parcours();
        $parcours->setNom($dto->nom);
        $parcours->setMention($mention);
        $parcours->setNiveau($niveau);

        return $parcours;
    }

    public function createFromDto(ParcoursDto $dto): Parcours
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $mention = $this->getVerifiedMention($dto->idMention);
            $niveau = $this->getVerifiedNiveau($dto->idNiveau);

            $parcours = $this->saveDto($dto, $mention, $niveau);

            $this->save($parcours);

            $this->em->getConnection()->commit();
            return $parcours;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function updateFromDto(int $id, ParcoursDto $dto): Parcours
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $ancien = $this->getVerifiedParcours($id);
            $mention = $this->getVerifiedMention($dto->idMention);
            $niveau = $this->getVerifiedNiveau($dto->idNiveau);

            // Soft delete de l'ancien
            $this->delete($ancien);

            // Création du nouveau
            $nouveau = $this->saveDto($dto, $mention, $niveau);

            $this->save($nouveau);

            $this->em->getConnection()->commit();
            return $nouveau;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    private function assignerUnEtudiant(NiveauEtudiants $ne, Parcours $parcours): array
    {
        $ne->setParcours($parcours);
        return $this->toArray($ne);
    }

    public function assignerParcours(AssignerParcoursDto $dto): array
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $parcours = $this->getVerifiedParcours($dto->idParcours);
            $assignes = [];

            foreach ($dto->idEtudiants as $idEtudiant) {
                $ne = $this->getVerifiedEtudiant($idEtudiant);
                $assignes[] = $this->assignerUnEtudiant($ne, $parcours);
            }

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $assignes;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function format(Parcours $parcours): array
    {
        $data = $parcours->toArray(['deletedAt']);
        
        $data['mention'] = [
            'id'  => $parcours->getMention()?->getId(),
            'nom' => $parcours->getMention()?->getNom(),
            'abr' => $parcours->getMention()?->getAbr(),
        ];
        $data['niveau'] = [
            'id'    => $parcours->getNiveau()?->getId(),
            'nom'   => $parcours->getNiveau()?->getNom(),
            'grade' => $parcours->getNiveau()?->getGrade(),
        ];
        return $data;
    }

    public function formatAll(array $parcoursList): array
    {
        return array_map(fn(Parcours $p) => $this->format($p), $parcoursList);
    }

    public function toArray(NiveauEtudiants $ne): array
    {
        return [
            'idNiveauEtudiant' => $ne->getId(),
            'idEtudiant'       => $ne->getEtudiant()?->getId(),
        ];
    }

}
