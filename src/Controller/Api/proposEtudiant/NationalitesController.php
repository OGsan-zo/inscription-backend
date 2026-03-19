<?php

namespace App\Controller\Api\proposEtudiant;

use App\Controller\Api\utils\BaseApiController;
use App\Service\proposEtudiant\NationaliteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nationalites')]
class NationalitesController extends BaseApiController
{
    private NationaliteService $nationaliteService;

    public function __construct(NationaliteService $nationaliteService) {
        $this->nationaliteService = $nationaliteService;
    }
    #[Route('', name:'all_nationalite', methods: ['GET'])]
    public function getUtilisateur(Request $request): JsonResponse
    {
        try {
   
            $data = $this->nationaliteService->getAllNationalitesArray();
            return $this->jsonSuccess($data);

        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(),  400);
        }
    }
}