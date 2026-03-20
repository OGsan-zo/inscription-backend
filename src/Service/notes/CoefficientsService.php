<?php

namespace App\Service\notes;

use App\Dto\notes\CoefficientUpdateDto;
use App\Dto\notes\MatiereMentionCoefficientDto;
use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Entity\note\MatiereMentionCoefficient;
use App\Entity\note\Matieres;
use App\Entity\proposEtudiant\Mentions;
use App\Entity\proposEtudiant\Niveaux;
use App\Entity\utilisateurs\Utilisateur;
use App\Repository\notes\MatiereMentionCoefficientRepository;
use App\Service\proposEtudiant\MentionsService;
use App\Service\proposEtudiant\NiveauService;
use App\Service\utilisateurs\UtilisateursService;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class CoefficientsService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly MatiereMentionCoefficientRepository $coefficientRepository,
        private readonly MatieresService $matieresService,
        private readonly MentionsService $mentionsService,
        private readonly NiveauService $niveauService,
        private readonly UtilisateursService $utilisateursService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): MatiereMentionCoefficientRepository
    {
        return $this->coefficientRepository;
    }

    // -------------------------------------------------------
    // Coefficients
    // -------------------------------------------------------

    public function getAllCoefficients(): array
    {
        return $this->coefficientRepository->getAll(new OrderCriteria('createdAt', 'ASC'));
    }

    private function buildCoefficient(Matieres $matiere, Mentions $mention, Niveaux $niveau,Utilisateur $professeur, int $coefficient): MatiereMentionCoefficient
    {
        $coeff = new MatiereMentionCoefficient();
        $coeff->setMatiere($matiere);
        $coeff->setMention($mention);
        $coeff->setCoefficient($coefficient);
        $coeff->setProfesseur($professeur);
        $coeff->setNiveau($niveau);
        return $coeff;
    }

    public function createCoefficient(MatiereMentionCoefficientDto $dto): MatiereMentionCoefficient
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere = $this->matieresService->getVerifierById($dto->idMatiere);
            $mention = $this->mentionsService->getVerifierById($dto->idMention);
            $niveau = $this->niveauService->getVerifierById($dto->idNiveau);
            $professeur = $this->utilisateursService->getVerifierById($dto->idProfesseur);
            
            $doublon = $this->coefficientRepository->findByMatiereAndMention(
                $matiere->getId(),
                $mention->getId(),
                $niveau->getId()
            );
            if ($doublon !== null) {
                throw new \Exception(
                    "Un coefficient existe déjà pour la matière \"{$matiere->getNom()}\" et la mention \"{$mention->getNom()}\".",
                    400
                );
            }

            $coeff = $this->buildCoefficient($matiere, $mention, $niveau, $professeur, $dto->coefficient);

            $this->em->persist($coeff);
            $this->em->flush();
            $this->em->getConnection()->commit();
            return $coeff;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function updateCoefficient(int $id, CoefficientUpdateDto $dto): MatiereMentionCoefficient
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $ancien = $this->getVerifierById($id);

            // Soft delete de l'ancien
            $this->delete($ancien);

            // Création du nouveau avec les mêmes matière + mention
            $nouveau = $this->buildCoefficient($ancien->getMatiere(), $ancien->getMention(), $ancien->getNiveau(), $ancien->getProfesseur(), $dto->coefficient);

            $this->em->persist($nouveau);
            $this->em->flush();
            $this->em->getConnection()->commit();
            return $nouveau;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function findByMentionAndSemestre(int $idMention, int $idSemestre): array
    {
        return $this->coefficientRepository->findByMentionAndSemestre($idMention, $idSemestre);
    }

    public function formatCoefficient(MatiereMentionCoefficient $c): array
    {
        $matiere = $c->getMatiere();
        $mention = $c->getMention();
        $niveau = $c->getNiveau();
        $niveauData = $this->niveauService->toArray($niveau);
        $professeur = $c->getProfesseur();
        $professeurData = $this->utilisateursService->toArray($professeur);
        return [
            'id'          => $c->getId(),
            'matiere'     => ['id' => $matiere?->getId(), 'name' => $matiere?->getName()],
            'semestre'    => [
                'id'  => $matiere?->getSemestre()?->getId(),
                'name' => $matiere?->getSemestre()?->getName(),
            ],
            'mention'     => $this->mentionsService->toArray($mention),
            'coefficient' => $c->getCoefficient(),
            'niveau'      => $niveauData,
            'professeur'  => $professeurData,
        ];
    }

    public function formatAllCoefficients(array $coefficients): array
    {
        return array_map(fn(MatiereMentionCoefficient $c) => $this->formatCoefficient($c), $coefficients);
    }
    public function getByProfesseur(Utilisateur $professeur): array
    {
        $conditions = [
            new ConditionCriteria('professeur', $professeur->getId(), '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
}
