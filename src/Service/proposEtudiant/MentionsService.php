<?php

namespace App\Service\proposEtudiant;
use App\Repository\proposEtudiant\MentionsRepository;
use App\Entity\proposEtudiant\Mentions;
use App\Service\utils\BaseService;
use App\Service\utils\ValidationService;
use Doctrine\ORM\EntityManagerInterface;

class MentionsService extends BaseService
{   private $mentionRepository;

    public function __construct(
        MentionsRepository $mentionRepository,
        EntityManagerInterface $em,
        ValidationService $validationService,
    ) {
        $this->mentionRepository = $mentionRepository;
        parent::__construct($em, $validationService);
    }

    public function getAllMentions(): array
    {
        return $this->mentionRepository->findAll();
    }

    protected function getRepository()
    {
        return $this->mentionRepository;
    }

    public function toArray(?Mentions $mention): array
    {
        if ($mention === null) {
            return [];
        }

        return [
            'id'    => $mention->getId(),
            'nom'   => $mention->getNom(),
            'abr' => $mention->getAbr(),
        ];
    }

    public function getAllMentionsExcept(array $excludedIds = []): array
    {
        return $this->mentionRepository->findAllExceptIds($excludedIds);
    }

    public function getById(int $id): ?Mentions
    {
        return $this->mentionRepository->find($id);
    }
}
