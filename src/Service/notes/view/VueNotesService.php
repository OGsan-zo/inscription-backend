<?php

namespace App\Service\notes\view;



use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\note\VueNotesEtudiantsRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueNotesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueNotesEtudiantsRepository $vueNotesEtudiantsRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueNotesEtudiantsRepository
    {
        return $this->vueNotesEtudiantsRepository;
    }
    public function getByMatiereCoefficientId(int $matiereMentionCoefficientId,int $annee): array
    {
        $conditions = [
            new ConditionCriteria('matiereMentionCoefficientId', $matiereMentionCoefficientId, '='),
            new ConditionCriteria('annee', $annee, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
}
