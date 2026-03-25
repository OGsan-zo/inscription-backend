<?php

namespace App\Service\proposEtudiant;
use App\Entity\utilisateurs\Utilisateur;
use App\Repository\proposEtudiant\MentionsRepository;
use App\Entity\proposEtudiant\Mentions;
use App\Service\utilisateurs\UtilisateursService;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class MentionsService extends BaseService
{   private  MentionsRepository $mentionRepository;
    private UtilisateursService $utilisateurService;

    public function __construct(
        MentionsRepository $mentionRepository,
        EntityManagerInterface $em,
        UtilisateursService $utilisateurService
    ) {
        $this->mentionRepository = $mentionRepository;
        parent::__construct($em);
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
            'chefMentionId' => $mention->getChefMention()?->getId(),
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
    public function getAllIdMentionParChefMention(Utilisateur $utilisateur): array
    {
        return $this->mentionRepository->getAllIdMentionParChefMention($utilisateur);
    }
    public function updateChefMention(int $mentionId,int $utilisateurId):Mentions
    {
        $mention = $this->getVerifierById($mentionId);
        $utilisateur = $this->utilisateurService->getUserById($utilisateurId);
        $mention->setChefMention($utilisateur);
        $mention = $this->save($mention);
        return $mention;
    }
}
