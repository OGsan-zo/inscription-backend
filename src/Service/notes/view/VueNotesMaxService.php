<?php

namespace App\Service\notes\view;

use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Entity\view\note\VueNotesMax;
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
    public function getByMatiereCoefficientIdEtudiant(int $etudiantId,int $matiereMentionCoefficientId,int $typeNoteId): ?VueNotesMax
    {
        $conditions = [
            new ConditionCriteria('etudiantId', $etudiantId, '='),
            new ConditionCriteria('matiereMentionCoefficientId', $matiereMentionCoefficientId, '='),
            new ConditionCriteria('typeNoteId', $typeNoteId, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result[0] ?? null;
        
    }
}
