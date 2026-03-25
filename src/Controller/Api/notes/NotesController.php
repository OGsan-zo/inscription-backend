<?php

namespace App\Controller\Api\notes;

use App\Annotation\TokenRequired;
use App\Controller\Api\utils\BaseApiController;
use App\Dto\notes\CoefficientUpdateDto;
use App\Dto\notes\MatiereDto;
use App\Dto\notes\MatiereMentionCoefficientDto;
use App\Dto\notes\NoteInsetionListeDto;
use App\Dto\notes\UEDto;
use App\Service\notes\CoefficientsService;
use App\Service\notes\MatieresService;
use App\Service\notes\NotesService;
use App\Service\notes\SemestresService;
use App\Service\notes\UEService;
use App\Service\notes\view\VueCoefficientDetailsService;
use App\Service\notes\view\VueNotesService;
use App\Service\proposEtudiant\view\VueNiveauEtudiantsDetailsService;
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
        private readonly VueNotesService $vueNotesService,
        private readonly VueCoefficientDetailsService $vueCoefficientDetailService,
        private readonly VueNiveauEtudiantsDetailsService $vueNiveauEtudiantsDetailsService
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
    #[Route('/ue/{id}', methods: ['PUT'])]
    #[TokenRequired(['Admin'])]
    public function updateUE(int $id, Request $request): JsonResponse
    {
        try {
            
            $dto= $this->deserializeAndValidate($request, UEDto::class);
            $ue = $this->ueService->updateDto($id, $dto);
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
            $excludedFields = ['createdAt', 'deletedAt'];
            $data = $this->semestresService->transformerArray($semestres, $excludedFields);
            return $this->jsonSuccess($data);
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
    #[TokenRequired(['Admin'])]
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
    
    #[Route('/matieres/{id}', methods: ['PUT'])]
    #[TokenRequired(['Admin'])]
    public function updateMatiere(int $id, Request $request): JsonResponse
    {
        try {
            $dto= $this->deserializeAndValidate($request, MatiereDto::class);
            $matiere = $this->matieresService->updateMatiere($id, $dto);
            return $this->jsonSuccess($this->matieresService->format($matiere), 201);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    

    // -------------------------------------------------------
    // GET /notes/matiere-semestres
    // -------------------------------------------------------
    #[Route('/matieres-coeff', methods: ['GET'])]
    #[TokenRequired(['ChefMention','Admin'])]
    public function coefficients(Request $request): JsonResponse
    {
        try {
            $utilisateur = $this->getUserFromRequest($request);
            $coefficients = $this->vueCoefficientDetailService->getByChefMention($utilisateur);
            $exludesFields = ['createdAt','deletedAt'];
            return $this->jsonSuccess($this->vueCoefficientDetailService->transformerArray($coefficients, $exludesFields));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/matieres-coeff/etudiant/{idMatiereCoeff}', methods: ['GET'])]
    #[TokenRequired(['ChefMention','Admin'])]
    public function coefficientsEtudiant(Request $request, int $idMatiereCoeff): JsonResponse
    {
        $annee = $request->query->get('annee');

        if (!$annee) {
            return $this->jsonError('Paramètre annee requis', 400);
        }
        try {
            $listeEtudiant = $this->vueNotesService->getByMatiereCoefficientId($idMatiereCoeff,$annee);
            $excludesFields = ['deletedAt'];
            $data = $this->vueNotesService->transformerArray($listeEtudiant, $excludesFields);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    // -------------------------------------------------------
    // POST /notes/matieres-coeff
    // -------------------------------------------------------
    #[Route('/matieres-coeff', methods: ['POST'])]
    #[TokenRequired(['Admin','ChefMention'])]
    public function createCoefficient(Request $request): JsonResponse
    {
        try {
            $dto   = $this->deserializeAndValidate($request, MatiereMentionCoefficientDto::class);
            $coeff = $this->coefficientsService->createCoefficient($dto);
            $excludes = ['createdAt', 'deletedAt'];
            return $this->jsonSuccess($coeff->toArray($excludes));
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
            $dto   = $this->deserializeAndValidate($request, MatiereMentionCoefficientDto::class);
            $coeff = $this->coefficientsService->updateCoefficient($id, $dto);
            $excludes = ['createdAt', 'deletedAt'];
            return $this->jsonSuccess($coeff->toArray($excludes));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // -------------------------------------------------------
    // 0 si false, 1 si true
    // GET /notes/resultats/{idEtudiant}?idSemestre=1&isCredit=false 
    // -------------------------------------------------------
    #[Route('/resultats/{idEtudiant}', methods: ['GET'])]
    #[TokenRequired]
    public function resultats(int $idEtudiant, Request $request): JsonResponse
    {
        try {
            $idSemestre = $request->query->get('idSemestre');
            $isCreditParam = $request->query->get('isCredit');

            if ($idSemestre === null) {
                return $this->jsonError("Le paramètre idSemestre est obligatoire.", 400);
            }

            // ✅ conversion propre en bool
            $isCredit = filter_var($isCreditParam, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            // valeur par défaut si null
            $isCredit = $isCredit ?? false;

            $data = $this->notesService->getNoteEtudiant(
                $idEtudiant,
                (int) $idSemestre,
                $isCredit
            );

            return $this->jsonSuccess($data);

        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/valider/{idNote}', methods: ['PUT'])]
    #[TokenRequired(['Admin', 'ChefMention'])]
    public function validerNote(int $idNote): JsonResponse
    {
        try {
            $note = $this->notesService->validerById($idNote);
            $exludesFields = ['createdAt','deletedAt'];
            return $this->jsonSuccess($note->toArray($exludesFields));
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    #[Route('/matieres-coeff/professeur', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Professeur'])]
    public function getMatiereCoeffProf(Request $request): JsonResponse
    {
        try {
            $user = $this->getUserFromRequest($request);
            $coefficients = $this->vueCoefficientDetailService->getByProfesseur($user);
            $exludesFields = ['createdAt','deletedAt'];
            $data = $this->vueCoefficientDetailService->transformerArray($coefficients, $exludesFields);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/matieres-coeff/professeur/{idMatiereCoeff}', methods: ['GET'])]
    #[TokenRequired(['Professeur','Admin'])]
    public function etudiantParMatiereCoeff(Request $request, int $idMatiereCoeff): JsonResponse
    {
        $annee = $request->query->get('annee');

        if (!$annee) {
            return $this->jsonError('Paramètre annee requis', 400);
        }
        try {
            $listeEtudiant = $this->vueNiveauEtudiantsDetailsService->getEtudiantByNiveauMentionDetail($idMatiereCoeff,$annee);
            $exludesFields = ['id','createdAt','deletedAt'];
            $data = $this->vueNiveauEtudiantsDetailsService->transformerArray($listeEtudiant, $exludesFields);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/matieres-coeff/professeur', methods: ['POST'])]
    #[TokenRequired(['Professeur','Admin'])]
    public function insertListeNoteProfesseur(Request $request): JsonResponse
    {
        try {
            $dto   = $this->deserializeAndValidate($request, NoteInsetionListeDto::class);
            $notes = $this->notesService->insertListeNoteProfesseurDto($dto);
            $exludesFields = ['id','createdAt','deletedAt'];
            $data = $this->vueNiveauEtudiantsDetailsService->transformerArray($notes, $exludesFields);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/matieres-coeff-detail', methods: ['GET'])]
    public function coefficientsDetail(Request $request): JsonResponse
    {
        $semestreId = $request->query->get('semestreId');
        $mentionId = $request->query->get('mentionId');
   
        try {
            $requiredFields = ['semestreId', 'mentionId'];
            $this->validatorService->validateRequiredFields(['semestreId' => $semestreId, 'mentionId' => $mentionId], $requiredFields);
            $coefficients = $this->vueCoefficientDetailService->getBySemestreIdMentionIdGroupedByUe($semestreId, $mentionId);
            return $this->jsonSuccess($coefficients);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
}
