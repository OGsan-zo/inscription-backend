<?php

namespace App\Controller\Api\payments;

use App\Controller\Api\utils\BaseApiController;
use App\Service\payment\EcolageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Service\payment\PaymentService;
use App\Annotation\TokenRequired;
use App\Dto\payment\PaymentRequestDto;

#[Route('/ecolage')]
class EcolageController extends BaseApiController
{
    public function __construct(
        private EcolageService $ecolageService,
        private PaymentService $paymentService
    ) {
    }

    #[Route('/etudiant/{id}/details', name: 'api_ecolage_etudiant_details', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Ecolage'])]
    public function getEtudiantDetails(int $id): JsonResponse
    {
        try {
            $data = $this->ecolageService->getStudentEcolageDetails($id);
            return $this->jsonSuccess($data);


        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/etudiant/{id}/history', name: 'api_ecolage_etudiant_history', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Ecolage'])]
    public function getEtudiantHistory(int $id): JsonResponse
    {
        try {
            $data = $this->ecolageService->getPaymentsHistory($id);

            return $this->jsonSuccess($data->toArray());

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
     

    #[Route('/payment/save', name: 'api_ecolage_payment_save', methods: ['POST'])]
    #[TokenRequired]
    public function savePayment(Request $request): JsonResponse
    {
        try {
            $utilisateur = $this->getUserFromRequest($request);
            $data = json_decode($request->getContent(), true);
            $requiredFields = ['etudiant_id', 'annee_scolaire', 'montant','ref_bordereau','date_paiement'];

            
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $missingFields[] = $field;
                }
            }
            if (!empty($missingFields)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Champs requis manquants ' . implode(', ', $missingFields),
                    'missingFields' => $missingFields
                ], 400);
            }


            $payment = $this->paymentService->processEcolagePayment($data, $utilisateur);
            $data =     [
                'id_paiement' => $payment->getId(),
                'reference' => $payment->getReference(),
                'montant' => $payment->getMontant()
            ];
            return $this->jsonSuccess($data);

        } catch (Exception $e) {
             return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/payment/annuler/{id}', name: 'api_paiements_annuler', methods: ['POST'])]
    public function annuler(int $id): JsonResponse
    {
        try {
            $this->paymentService->annulerPaiement($id);

            return $this->jsonSuccess([
                'message' => 'Paiement annulé avec succès'
            ]);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/modifier-paiement', name: 'ecolage_modifier_paiement', methods: ['POST'])]
    #[TokenRequired(['Admin', 'Utilisateur', 'Ecolage'])]
    public function modifierPaiement(Request $request): JsonResponse
    {
        try {
            $utilisateur = $this->getUserFromRequest($request);
            $dto = $this->deserializeAndValidate(
                $request,
                PaymentRequestDto::class,
            );
            
            $newPayment = $this->paymentService->modifierPayment($utilisateur, $dto);
            $data = [
                    'id' => $newPayment->getId(),
                    'montant' => $newPayment->getMontant(),
                    'reference' => $newPayment->getReference(),
                    'datePaiement' => $newPayment->getDatePayment()->format('Y-m-d'),
                    'typeDroit' => $newPayment->getType() ? $newPayment->getType()->getNom() : null
                ];
            return $this->jsonSuccess($data);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/etudiant/{id}/all-detail', name: 'api_ecolage_etudiant_all_detail', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Ecolage'])]
    public function getAllDetailEcolage(int $id): JsonResponse
    {
        try {
            
            $data = $this->ecolageService->getPaymentsHistory($id);
            $details = $this->ecolageService->getStudentEcolageDetails($id);
            $valiny = $data->toArray();
            $valiny['details'] = $details;
            return $this->jsonSuccess($valiny);

        } catch (Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

}
