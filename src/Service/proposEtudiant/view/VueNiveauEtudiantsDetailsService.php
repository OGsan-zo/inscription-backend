<?php

namespace App\Service\proposEtudiant\view;



use App\Dto\notes\view\NiveauEtudiantDetailsDto;
use App\Dto\utils\ConditionCriteria;
use App\Dto\utils\OrderCriteria;
use App\Repository\view\proposEtudiant\VueNiveauEtudiantsDetailsRepository;
use App\Service\notes\CoefficientsService;
use App\Service\notes\view\VueDernierNotesService;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class VueNiveauEtudiantsDetailsService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly VueNiveauEtudiantsDetailsRepository $vueNiveauEtudiantsDetailsRepository,
        private readonly VueDernierNotesService $vueDernierNoteService,
        private readonly CoefficientsService $coefficientsService,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): VueNiveauEtudiantsDetailsRepository
    {
        return $this->vueNiveauEtudiantsDetailsRepository;
    }
    public function getEtudiantByNiveauMention(int $niveauId, int $mentionId,int $annee): array
    {
        $conditions = [
            new ConditionCriteria('niveauId', $niveauId, '='),
            new ConditionCriteria('mentionId', $mentionId, '='),
            new ConditionCriteria('annee', $annee, '='),
        ];
        $orderCriteria = new OrderCriteria('createdAt', 'DESC');

        
        $result = $this->search($conditions, $orderCriteria);
        return $result;
    }
    public function getEtudiantByNiveauMentionDetail(int $matiereMentionCoefficientId,int $annee): array{
        $result = [];
        $matiereMentionCoefficient = $this->coefficientsService->getById($matiereMentionCoefficientId);
        
        $listeNiveaux = $this->getEtudiantByNiveauMention($matiereMentionCoefficient->niveauId, $matiereMentionCoefficient->mentionId, $annee);
        foreach ($listeNiveaux as $ls) {
            $vueNiveauDetailDto = new NiveauEtudiantDetailsDto();
            $vueNiveauDetailDto->details = $ls;
            $vueNiveauDetailDto->notes = $this->vueDernierNoteService->getByMatiereCoefficientId($matiereMentionCoefficientId, $annee);
            $result[] = $vueNiveauDetailDto;
        }   
        return $result;
    }
}
