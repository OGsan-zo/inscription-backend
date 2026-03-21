<?php

namespace App\Service\notes\view;



use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\note\VueNotesMaxRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueNotesMaxService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueNotesMaxRepository $vueNotesMaxRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueNotesMaxRepository
    {
        return $this->vueNotesMaxRepository;
    }
    public function getByMatiereCoefficientIdEtudiant(int $etudiantId,int $matiereMentionCoefficientId): array
    {
        $conditions = [
            new ConditionCriteria('etudiantId', $etudiantId, '='),
            new ConditionCriteria('matiereMentionCoefficientId', $matiereMentionCoefficientId, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
}
