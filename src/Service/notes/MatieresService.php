<?php

namespace App\Service\notes;

use App\Dto\notes\MatiereDto;
use App\Dto\notes\MatiereSemestreDto;
use App\Dto\utils\OrderCriteria;
use App\Entity\Matieres;
use App\Repository\notes\MatieresRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class MatieresService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly MatieresRepository $matieresRepository,
        private readonly SemestresService $semestresService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): MatieresRepository
    {
        return $this->matieresRepository;
    }

    // -------------------------------------------------------
    // Matieres
    // -------------------------------------------------------

    public function getAllMatieres(): array
    {
        return $this->matieresRepository->getAll(new OrderCriteria('nom', 'ASC'));
    }

    public function createMatiere(MatiereDto $dto): Matieres
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere = new Matieres();
            $matiere->setNom($dto->nom);
            $this->save($matiere);
            $this->em->getConnection()->commit();
            return $matiere;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function format(Matieres $m): array
    {
        return $m->toArray(['deletedAt', 'createdAt']);
    }

    public function formatAll(array $matieres): array
    {
        return array_map(fn(Matieres $m) => $this->format($m), $matieres);
    }

    // -------------------------------------------------------
    // Liaison Matière–Semestre
    // -------------------------------------------------------

    public function getAllMatiereSemestres(): array
    {
        return $this->matieresRepository->findAvecSemestre();
    }

    public function assignerSemestre(MatiereSemestreDto $dto): Matieres
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $matiere  = $this->getVerifierById($dto->idMatiere);
            $semestre = $this->semestresService->getVerifierById($dto->idSemestre);

            if ($matiere->getSemestre() !== null) {
                throw new \Exception(
                    "La matière \"{$matiere->getNom()}\" est déjà associée à un semestre.",
                    400
                );
            }

            $matiere->setSemestre($semestre);
            $this->save($matiere);
            $this->em->getConnection()->commit();
            return $matiere;
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }

    public function formatMatiereSemestre(Matieres $m): array
    {
        return [
            'id'       => $m->getId(),
            'matiere'  => ['id' => $m->getId(), 'nom' => $m->getNom()],
            'semestre' => [
                'id'  => $m->getSemestre()?->getId(),
                'nom' => $m->getSemestre()?->getNom(),
            ],
        ];
    }

    public function formatAllMatiereSemestres(array $matieres): array
    {
        return array_map(fn(Matieres $m) => $this->formatMatiereSemestre($m), $matieres);
    }
}
