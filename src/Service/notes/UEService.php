<?php

namespace App\Service\notes;


use App\Dto\notes\UEDto;
use App\Entity\note\UE;
use App\Repository\notes\UERepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class UEService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UERepository $ueRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): UERepository
    {
        return $this->ueRepository;
    }
    public function saveDto(UEDto $dto): UE{
        $ue = new UE();
        $ue->setName($dto->name);
        $this->save($ue);
        return $ue;
    }
}