<?php

namespace App\Controller\Api\parcours;

use App\Annotation\TokenRequired;
use App\Controller\Api\utils\BaseApiController;
use App\Dto\parcours\AssignerParcoursDto;
use App\Dto\parcours\ParcoursDto;
use App\Dto\utils\OrderCriteria;
use App\Service\parcours\ParcoursService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/parcours')]
class ParcoursController extends BaseApiController
{
    public function __construct(private readonly ParcoursService $parcoursService)
    {
    }

    #[Route('', methods: ['GET'])]
    #[TokenRequired]
    public function index(): JsonResponse
    {
        try {
            $parcours = $this->parcoursService->getAll(new OrderCriteria('createdAt', 'DESC'));
            return $this->jsonSuccess($this->parcoursService->formatAll($parcours));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/assigner', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function assigner(Request $request): JsonResponse
    {
        try {
            $dto = $this->deserializeAndValidate($request, AssignerParcoursDto::class);
            $result = $this->parcoursService->assignerParcours($dto);
            return $this->jsonSuccess($result);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    #[TokenRequired]
    public function show(int $id): JsonResponse
    {
        try {
            $parcours = $this->parcoursService->getById($id);
            $this->validatorService->throwIfNull($parcours, "Parcours introuvable pour l'ID $id.");
            return $this->jsonSuccess($this->parcoursService->format($parcours));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $dto = $this->deserializeAndValidate($request, ParcoursDto::class);
            $parcours = $this->parcoursService->createFromDto($dto);
            return $this->jsonSuccess($this->parcoursService->format($parcours), 201);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[TokenRequired(['Admin'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $dto = $this->deserializeAndValidate($request, ParcoursDto::class);
            $parcours = $this->parcoursService->updateFromDto($id, $dto);
            return $this->jsonSuccess($this->parcoursService->format($parcours));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[TokenRequired(['Admin'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $parcours = $this->parcoursService->getById($id);
            $this->validatorService->throwIfNull($parcours, "Parcours introuvable pour l'ID $id.");
            $this->parcoursService->delete($parcours);
            return $this->jsonSuccess(['message' => 'Parcours supprimé avec succès.']);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
