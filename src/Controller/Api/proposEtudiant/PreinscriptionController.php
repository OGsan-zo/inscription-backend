<?php

namespace App\Controller\Api\proposEtudiant;

use App\Controller\Api\utils\BaseApiController;
use App\Service\inscription\PreinscriptionService;
use App\Dto\inscription\PreinscriptionRequestDto;
use App\Dto\proposEtudiant\EtudiantRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use App\Annotation\TokenRequired;

#[Route('/pre-inscription')]
class PreinscriptionController extends BaseApiController
{
    private PreinscriptionService $preinscriptionService;
    

    public function __construct(
        PreinscriptionService $preinscriptionService,
     ) {
        $this->preinscriptionService = $preinscriptionService;
    }

    /**
     * POST /api/pre-inscription/save
     * Sauvegarde une nouvelle préinscription
     */
    #[Route('/save', name: 'preinscription_save', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function save(Request $request): JsonResponse
    {
        try {
             $dto = $this->deserializeAndValidate(
                $request,
                PreinscriptionRequestDto::class
            );
            
            $id = $this->preinscriptionService->savePreinscription($dto);
            return $this->jsonSuccess($id);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    /**
     * GET /api/pre-inscription/search
     * Retourne toutes les préinscriptions actives (non converties)
     */
    #[Route('/search', name: 'preinscription_search', methods: ['POST'])]
    #[TokenRequired(['Utilisateur', 'Admin'])]
    public function search(): JsonResponse
    {
        try {
            $preinscriptions = $this->preinscriptionService->getActivePreinscriptions();

            $data = array_map(fn($p) => $p->toArray(), $preinscriptions);

            return $this->jsonSuccess($data);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 500);
        }
    }

    /**
     * POST /api/pre-inscription/search-filter
     * Recherche filtrée par nom et/ou prénom
     * Body attendu : { "nom": "...", "prenom": "..." }
     */
    #[Route('/search-filter', name: 'preinscription_search_filter', methods: ['POST'])]
    #[TokenRequired(['Utilisateur', 'Admin'])]
    public function rechercheParNomPrenom(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $nom = isset($data['nom']) ? trim((string) $data['nom']) : '';
            $prenom = isset($data['prenom']) ? trim((string) $data['prenom']) : '';

            // Si les deux champs sont vides, on renvoie tout
            if ($nom === '' && $prenom === '') {
                $preinscriptions = $this->preinscriptionService->getActivePreinscriptions();
            } else {
                $preinscriptions = $this->preinscriptionService->searchByCriteria(
                    $nom !== '' ? $nom : null,
                    $prenom !== '' ? $prenom : null
                );
            }

            $result = array_map(fn($p) => $p->toArray(), $preinscriptions);

            return $this->jsonSuccess($result);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    /**
     * POST /api/pre-inscription/convertir
     * Convertit une préinscription en inscription complète
     * 
     * Body attendu:
     * {
     *   "preInscriptionId": 123,
     *   "etudiantData": { ... EtudiantRequestDto incomplet ... }
     * }
     */
    #[Route('/convertir', name: 'preinscription_convertir', methods: ['POST'])]
    #[TokenRequired(['Utilisateur', 'Admin'])]
    public function convertir(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['preInscriptionId'])) {
                return $this->jsonError('Le champ preInscriptionId est requis', 400);
            }

            if (!isset($data['etudiantData'])) {
                return $this->jsonError('Le champ etudiantData est requis', 400);
            }

            $preInscriptionId = (int) $data['preInscriptionId'];

            // Désérialiser le DTO étudiant
            $etudiantDto = $this->serializer->deserialize(
                json_encode($data['etudiantData']),
                EtudiantRequestDto::class,
                'json'
            );

            // Convertir la préinscription
            $etudiantId = $this->preinscriptionService->convertir($preInscriptionId, $etudiantDto);

            return $this->json([
                'status' => 'success',
                'message' => 'Préinscription convertie en inscription avec succès',
                'etudiantId' => $etudiantId
            ], 201);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
}
