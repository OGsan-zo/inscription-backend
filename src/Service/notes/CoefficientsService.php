<?php

namespace App\Service\notes;

use App\Dto\notes\CoefficientUpdateDto;
use App\Dto\notes\MatiereMentionCoefficientDto;
use App\Dto\utils\OrderCriteria;
use App\Entity\MatiereMentionCoefficient;
use App\Entity\Mentions;
use App\Repository\notes\MatiereMentionCoefficientRepository;
use App\Service\proposEtudiant\MentionsService;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class CoefficientsService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly MatiereMentionCoefficientRepository $coefficientRepository,
        private readonly MatieresService $matieresService,
        private readonly MentionsService $mentionsService,
        private readonly ValidationService $validationService,
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

    public function getVerifiedCoefficient(int $id): MatiereMentionCoefficient
    {
        $coeff = $this->coefficientRepository->find($id);
        $this->validationService->throwIfNull($coeff, "Coefficient introuvable pour l'ID $id.");
        return $coeff;
    }

    public function getVerifiedMention(int $id): Mentions
    {
        $mention = $this->mentionsService->getById($id);
        $this->validationService->throwIfNull($mention, "Mention introuvable pour l'ID $id.");
        return $mention;
    }

    private function buildCoefficient(\App\Entity\Matieres $matiere, Mentions $mention, int $coefficient): MatiereMentionCoefficient
    {
        $coeff = new MatiereMentionCoefficient();
        $coeff->setMatiere($matiere);
        $coeff->setMention($mention);
        $coeff->setCoefficient($coefficient);
        return $coeff;
    }

    public function createCoefficient(MatiereMentionCoefficientDto $dto): MatiereMentionCoefficient
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere = $this->matieresService->getVerifiedMatiere($dto->idMatiere);
            $mention = $this->getVerifiedMention($dto->idMention);

            $doublon = $this->coefficientRepository->findByMatiereAndMention(
                $matiere->getId(),
                $mention->getId()
            );
            if ($doublon !== null) {
                throw new \Exception(
                    "Un coefficient existe déjà pour la matière \"{$matiere->getNom()}\" et la mention \"{$mention->getNom()}\".",
                    400
                );
            }

            $coeff = $this->buildCoefficient($matiere, $mention, $dto->coefficient);

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
            $ancien = $this->getVerifiedCoefficient($id);

            // Soft delete de l'ancien
            $this->delete($ancien);

            // Création du nouveau avec les mêmes matière + mention
            $nouveau = $this->buildCoefficient($ancien->getMatiere(), $ancien->getMention(), $dto->coefficient);

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
        return [
            'id'          => $c->getId(),
            'matiere'     => ['id' => $matiere?->getId(), 'nom' => $matiere?->getNom()],
            'semestre'    => [
                'id'  => $matiere?->getSemestre()?->getId(),
                'nom' => $matiere?->getSemestre()?->getNom(),
            ],
            'mention'     => $this->mentionsService->toArray($mention),
            'coefficient' => $c->getCoefficient(),
        ];
    }

    public function formatAllCoefficients(array $coefficients): array
    {
        return array_map(fn(MatiereMentionCoefficient $c) => $this->formatCoefficient($c), $coefficients);
    }
}
