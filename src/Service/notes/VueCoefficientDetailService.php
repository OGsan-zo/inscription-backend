<?php

namespace App\Service\notes;



use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\note\VueMatiereCoeffDetailRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueCoefficientDetailService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueMatiereCoeffDetailRepository $vueMatiereCoeffDetailRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueMatiereCoeffDetailRepository
    {
        return $this->vueMatiereCoeffDetailRepository;
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
