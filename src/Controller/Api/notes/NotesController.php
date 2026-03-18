<?php

namespace App\Controller\Api\notes;

use App\Annotation\TokenRequired;
use App\Controller\Api\utils\BaseApiController;
use App\Dto\notes\MatiereDto;
use App\Dto\notes\MatiereSemestreDto;
use App\Service\notes\NotesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notes')]
class NotesController extends BaseApiController
{
    public function __construct(private readonly NotesService $notesService)
    {
    }

    // -------------------------------------------------------
    // GET /notes/semestres
    // -------------------------------------------------------
    #[Route('/semestres', methods: ['GET'])]
    public function semestres(): JsonResponse
    {
        try {
            $semestres = $this->notesService->getAllSemestres();
            return $this->jsonSuccess($this->notesService->formatAllSemestres($semestres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // GET /notes/matieres
    // -------------------------------------------------------
    #[Route('/matieres', methods: ['GET'])]
    public function matieres(): JsonResponse
    {
        try {
            $matieres = $this->notesService->getAllMatieres();
            return $this->jsonSuccess($this->notesService->formatAll($matieres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // POST /notes/matieres
    // -------------------------------------------------------
    #[Route('/matieres', methods: ['POST'])]
    #[TokenRequired]
    public function createMatiere(Request $request): JsonResponse
    {
        try {
            $dto = $this->deserializeAndValidate($request, MatiereDto::class);
            $matiere = $this->notesService->createMatiere($dto);
            return $this->jsonSuccess($this->notesService->format($matiere), 201);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // GET /notes/matiere-semestres
    // -------------------------------------------------------
    #[Route('/matiere-semestres', methods: ['GET'])]
    public function matiereSemestres(): JsonResponse
    {
        try {
            $matieres = $this->notesService->getAllMatiereSemestres();
            return $this->jsonSuccess($this->notesService->formatAllMatiereSemestres($matieres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // POST /notes/matiere-semestres
    // -------------------------------------------------------
    #[Route('/matiere-semestres', methods: ['POST'])]
    #[TokenRequired]
    public function assignerSemestre(Request $request): JsonResponse
    {
        try {
            $dto = $this->deserializeAndValidate($request, MatiereSemestreDto::class);
            $matiere = $this->notesService->assignerSemestre($dto);
            return $this->jsonSuccess($this->notesService->formatMatiereSemestre($matiere), 201);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
