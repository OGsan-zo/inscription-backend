<?php

namespace App\Service\notes\view;



use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\note\VueMatiereCoeffDetailsRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueCoefficientDetailsService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueMatiereCoeffDetailsRepository $vueMatiereCoeffDetailsRepository,
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
    public function getByProfesseur($professeur): array
    {
        $conditions = [
            new ConditionCriteria('professeurId', $professeur->getId(), '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
}
