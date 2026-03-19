<?php

namespace App\Controller\Api\proposEtudiant;

use App\Controller\Api\utils\BaseApiController;
use App\Service\proposEtudiant\EtudiantsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\proposEtudiant\NiveauEtudiantsService;
use App\Annotation\TokenRequired;
#[Route('/filtres')]
class FiltresController extends BaseApiController
{
    private NiveauEtudiantsService $niveauEtudiantsService;
    private EtudiantsService $etudiantsService;

    public function __construct(NiveauEtudiantsService $niveauEtudiantsService,EtudiantsService $etudiantsService)
    {
        $this->niveauEtudiantsService = $niveauEtudiantsService;
        $this->etudiantsService = $etudiantsService;
    }

    #[Route('/etudiant', name: 'filtre_etudiant', methods: ['GET'])]
    #[TokenRequired]    
    public function getUtilisateur(Request $request): JsonResponse
    {
        try {
            $date = new \DateTime();
            $annee = (int) $date->format('Y');


            // 1. Récupération des critères de filtrage depuis l'URL
            $idMention = $request->query->get('idMention');
            $idNiveau = $request->query->get('idNiveau');
            $idParcours = $request->query->get('idParcours');

            // 2. Récupération de tous les étudiants de l'année
            $niveauEtudiants = $this->niveauEtudiantsService->getAllNiveauEtudiantAnnee($annee,$idMention,$idNiveau,$idParcours ? (int) $idParcours : null);

            $data = array_map(function ($e) {
                $etudiant = $e->getEtudiant();
                $mention = $e->getMention();
                $niveau = $e->getNiveau();
                $parcours = $e->getParcours();
                return [
                    'id' => $etudiant?->getId(),
                    'nom' => $etudiant?->getNom(),
                    'prenom' => $etudiant?->getPrenom(),
                    'mention' => $mention?->getNom(),
                    'mentionAbr' => $mention?->getAbr(),
                    'idMention' => $mention?->getId(),
                    'niveau' => $niveau?->getNom(),
                    'idNiveau' => $niveau?->getId(),
                    'matricule' => $e->getMatricule() ?? '',
                    'dateInsertion' => $e->getDateInsertion()?->format('Y-m-d H:i:s'),
                    'idParcours' => $parcours?->getId(),
                    'nomParcours' => $parcours?->getNom(),
                ];
            }, array_values($niveauEtudiants));

                return $this->jsonSuccess($data);

        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(),  400);
        }
    }
    #[Route('/etudiant/export', name: 'filtre_etudiant_export', methods: ['GET'])]
    // #[TokenRequired]
    public function getUtilisateurExport(Request $request): JsonResponse
    {
        try {
            $date = new \DateTime();
            $annee = (int) $date->format('Y');


            // 1. Récupération des critères de filtrage depuis l'URL
            $idMention = $request->query->get('idMention');
            $idNiveau = $request->query->get('idNiveau');
            $idParcours = $request->query->get('idParcours');
            $limit = (int) $request->query->get('limit', 10000);

            // 2. Récupération de tous les étudiants de l'année
            $niveauEtudiants = $this->niveauEtudiantsService->getAllNiveauEtudiantAnnee($annee,$idMention,$idNiveau,$idParcours ? (int) $idParcours : null,$limit);

            $data = $this->etudiantsService->toArrayListeNiveauEtudiants($niveauEtudiants);
            return $this->jsonSuccess($data);

        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(),  400);
        }
    }
}