<?php

namespace App\Service\notes\view;



use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\note\VueDernierNotesRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueDernierNotesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueDernierNotesRepository $vueDernierNotesRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueDernierNotesRepository
    {
        return $this->vueDernierNotesRepository;
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
    public function getByMatiereCoefficientIdEtudiant(int $etudiantId,int $matiereMentionCoefficientId,int $annee): array
    {
        $conditions = [
            new ConditionCriteria('etudiantId', $etudiantId, '='),
            new ConditionCriteria('matiereMentionCoefficientId', $matiereMentionCoefficientId, '='),
            new ConditionCriteria('annee', $annee, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
        
    }
}
