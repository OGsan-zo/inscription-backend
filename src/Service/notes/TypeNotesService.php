<?php

namespace App\Service\notes;


use App\Repository\notes\TypeNotesRepository;
use App\Service\utils\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class TypeNotesService extends BaseService
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly TypeNotesRepository $typeNotesRepository,
    ) {
        parent::__construct($em);
    }

    protected function getRepository(): TypeNotesRepository
    {
        return $this->typeNotesRepository;
    }
}