<?php

namespace App\Service\notes\view;



use App\Dto\notes\MatiereCoefficientDetailDto;
use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Entity\utilisateurs\Utilisateur;
use App\Repository\view\note\VueMatiereCoeffDetailsRepository;
use App\Service\proposEtudiant\MentionsService;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueCoefficientDetailsService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueMatiereCoeffDetailsRepository $vueMatiereCoeffDetailsRepository,
        private readonly MentionsService $mentionsService
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueMatiereCoeffDetailsRepository
    {
        return $this->vueMatiereCoeffDetailsRepository;
    }
    public function getByMentionId(int $mentionId): array
    {
        $conditions = [
            new ConditionCriteria('mentionId', $mentionId, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
    public function getByProfesseur(Utilisateur $professeur): array
    {
        $conditions = [
            new ConditionCriteria('professeurId', $professeur->getId(), '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
    public function getByChefMention(Utilisateur $chefMention): array
    {
        $listeIdMention = $this->mentionsService->getAllIdMentionParChefMention($chefMention);

        $conditions = [
            new ConditionCriteria('mentionId', $listeIdMention, 'IN'),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
    public function getBySemestreIdMentionId(int $semestreId,int $mentionId): array
    {
        $conditions = [
            new ConditionCriteria('semestreId', $semestreId, '='),
            new ConditionCriteria('mentionId', $mentionId, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
    public function regrouperParUe(array $viewMatiereCoefficientDetail): array
    {
        $result = [];

        foreach ($viewMatiereCoefficientDetail as $item) {
            $ue = $item->getUe() ?? 'Sans UE';

            // Si UE n'existe pas encore
            if (!isset($result[$ue])) {
                $dto = new MatiereCoefficientDetailDto();
                $dto->setUe($ue);
                $dto->setMatiereCoefficients([]);

                $result[$ue] = $dto;
            }

            // Ajouter la matière dans le groupe UE
            $result[$ue]->matiereCoefficients[] = $item;
        }

        // Réindexer en tableau simple
        return array_values($result);
    }
    public function getBySemestreIdMentionIdGroupedByUe(int $semestreId,int $mentionId): array
    {
        $listeMatiereCoefficientDetail = $this->getBySemestreIdMentionId($semestreId, $mentionId);
        return $this->regrouperParUe($listeMatiereCoefficientDetail);
    }
    public function getAllMentionParChefMention(Utilisateur $utilisateur): array
    {
        $listeMatiereCoefficientDetail = $this->getByProfesseur($utilisateur);
        return $this->regrouperParUe($listeMatiereCoefficientDetail);
    }

}
