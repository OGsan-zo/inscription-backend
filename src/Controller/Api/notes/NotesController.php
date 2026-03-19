<?php

namespace App\Controller\Api\notes;

use App\Annotation\TokenRequired;
use App\Controller\Api\utils\BaseApiController;
use App\Dto\notes\CoefficientUpdateDto;
use App\Dto\notes\MatiereDto;
use App\Dto\notes\MatiereMentionCoefficientDto;
use App\Dto\notes\MatiereSemestreDto;
use App\Dto\notes\NoteUpdateDto;
use App\Dto\notes\UEDto;
use App\Service\notes\CoefficientsService;
use App\Service\notes\MatieresService;
use App\Service\notes\NotesService;
use App\Service\notes\SemestresService;
use App\Service\notes\UEService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notes')]
class NotesController extends BaseApiController
{
    public function __construct(
        private readonly SemestresService $semestresService,
        private readonly MatieresService $matieresService,
        private readonly CoefficientsService $coefficientsService,
        private readonly NotesService $notesService,
        private readonly UEService $ueService,
    ) {
    }

    #[Route('/ue', methods: ['GET'])]
    public function ue(): JsonResponse
    {
        try {
            $ues = $this->ueService->getAll();
            $excludedFields = ['createdAt', 'deletedAt'];
            $data = $this->ueService->transformerArray($ues, $excludedFields);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/ue', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function createUE(Request $request): JsonResponse
    {
        try {
            $dto= $this->deserializeAndValidate($request, UEDto::class);
            $ue = $this->ueService->saveDto($dto);
            $excludedFields = ['createdAt', 'deletedAt'];
            return $this->jsonSuccess($ue->toArray($excludedFields));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/semestres', methods: ['GET'])]
    public function semestres(): JsonResponse
    {
        try {
            $semestres = $this->semestresService->getAllSemestres();
            return $this->jsonSuccess($this->semestresService->formatAllSemestres($semestres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/matieres', methods: ['GET'])]
    public function matieres(): JsonResponse
    {
        try {
            $matieres = $this->matieresService->getAllMatieres();
            return $this->jsonSuccess($this->matieresService->formatAll($matieres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    
    #[Route('/matieres', methods: ['POST'])]
    // #[TokenRequired(['Admin'])]
    public function createMatiere(Request $request): JsonResponse
    {
        try {
            $dto= $this->deserializeAndValidate($request, MatiereDto::class);
            $matiere = $this->matieresService->createMatiere($dto);
            return $this->jsonSuccess($this->matieresService->format($matiere), 201);
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
            $matieres = $this->matieresService->getAllMatiereSemestres();
            return $this->jsonSuccess($this->matieresService->formatAllMatiereSemestres($matieres));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    #[Route('/matieres-coeff', methods: ['GET'])]
    public function coefficients(): JsonResponse
    {
        try {
            $coefficients = $this->coefficientsService->getAllCoefficients();
            return $this->jsonSuccess($this->coefficientsService->formatAllCoefficients($coefficients));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // POST /notes/matieres-coeff
    // -------------------------------------------------------
    #[Route('/matieres-coeff', methods: ['POST'])]
    #[TokenRequired]
    public function createCoefficient(Request $request): JsonResponse
    {
        try {
            $dto   = $this->deserializeAndValidate($request, MatiereMentionCoefficientDto::class);
            $coeff = $this->coefficientsService->createCoefficient($dto);
            return $this->jsonSuccess($this->coefficientsService->formatCoefficient($coeff), 201);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // PUT /notes/matieres-coeff/{id}
    // -------------------------------------------------------
    #[Route('/matieres-coeff/{id}', methods: ['PUT'])]
    #[TokenRequired]
    public function updateCoefficient(int $id, Request $request): JsonResponse
    {
        try {
            $dto   = $this->deserializeAndValidate($request, CoefficientUpdateDto::class);
            $coeff = $this->coefficientsService->updateCoefficient($id, $dto);
            return $this->jsonSuccess($this->coefficientsService->formatCoefficient($coeff));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // GET /notes/resultats/{idEtudiant}?idSemestre=
    // -------------------------------------------------------
    #[Route('/resultats/{idEtudiant}', methods: ['GET'])]
    #[TokenRequired]
    public function resultats(int $idEtudiant, Request $request): JsonResponse
    {
        try {
            $idSemestre = $request->query->get('idSemestre');
            if ($idSemestre === null) {
                return $this->jsonError("Le paramètre idSemestre est obligatoire.", 400);
            }
            $data = $this->notesService->getResultatsEtudiant($idEtudiant, (int) $idSemestre);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // PUT /notes/resultats/{idNote}
    // -------------------------------------------------------
    #[Route('/resultats/{idNote}', methods: ['PUT'])]
    #[TokenRequired]
    public function updateNote(int $idNote, Request $request): JsonResponse
    {
        try {
            $dto  = $this->deserializeAndValidate($request, NoteUpdateDto::class);
            $note = $this->notesService->updateNote($idNote, $dto);
            $mmc  = $note->getMatiereMentionCoefficient();
            return $this->jsonSuccess($this->notesService->formatLigneResultat($mmc, $note));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
