<?php

namespace App\Controller\Api;

use App\Controller\Api\utils\BaseApiController;
use App\Entity\Etudiants;
use App\Entity\Payments;
use App\Service\inscription\InscriptionService;
use App\Service\proposEtudiant\EtudiantsService;
use App\Service\proposEtudiant\FormationEtudiantsService;
use App\Service\proposEtudiant\NiveauEtudiantsService;
use App\Service\proposEtudiant\MentionsService;
use App\Annotation\TokenRequired;
use App\Dto\EtudiantRequestDto;
use App\Service\payment\PaymentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\etudiant\NiveauEtudiantRequestDto;

#[Route('/etudiants')]
class EtudiantsController extends BaseApiController
{
    private EtudiantsService $etudiantsService;
    private NiveauEtudiantsService $niveauEtudiantsService;
    private FormationEtudiantsService $formationEtudiantsService;
    private InscriptionService $inscriptionService;
    private MentionsService $mentionsService;
    private PaymentService $paymentService;


    public function __construct(
        EtudiantsService $etudiantsService,
        NiveauEtudiantsService $niveauEtudiantsService,
        FormationEtudiantsService $formationEtudiantsService,
        InscriptionService $inscriptionService,
        MentionsService $mentionsService,
        PaymentService $paymentService,
    ) {
        $this->etudiantsService = $etudiantsService;
        $this->niveauEtudiantsService = $niveauEtudiantsService;
        $this->formationEtudiantsService = $formationEtudiantsService;
        $this->inscriptionService = $inscriptionService;
        $this->mentionsService = $mentionsService;
        $this->paymentService = $paymentService;
    }

    #[Route('/recherche', name: 'etudiant_recherche', methods: ['POST'])]
    #[TokenRequired]
    public function getEtudiants(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $requiredFields = ['nom', 'prenom'];
            $this->validatorService->validateRequiredFields($data, $requiredFields);
            $nom = $data['nom'];
            $prenom = $data['prenom'];

            $etudiants = $this->etudiantsService->rechercheEtudiant($nom, $prenom);

            if (empty($etudiants)) {
                return $this->jsonError('Étudiant non trouvé', 404);
            }

            $data = $this->etudiantsService->transformerArray($etudiants);
            return $this->jsonSuccess($data);


        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(), 400);
        }

    }

    #[Route('', name: 'etudiant_show', methods: ['GET'])]
    #[TokenRequired]
    public function getEtudiantParId(Request $request): JsonResponse
    {
        try {
            $idEtudiant = $request->query->get('idEtudiant');

            if (!$idEtudiant) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Paramètre idEtudiant requis'

                ], 400);
            }
            $token = $this->jwtManager->extractTokenFromRequest($request);
            $arrayToken = $this->jwtManager->extractClaimsFromToken($token);
            $role = $arrayToken['role'];
            $idEtudiant = (int) $idEtudiant;
            $date = new \DateTime(); // ou une autre date
            $annee = (int) $date->format('Y');
            $recherche = ["Admin", "Utilisateur"];

            if (in_array($role, $recherche)) {
                $dejaInscrit = $this->inscriptionService->dejaInscritEtudiantAnneeId($idEtudiant, $annee);
                if ($dejaInscrit) {
                    return $this->jsonError('Étudiant deja inscrit', 400);

                }
            }
            $data = $this->etudiantsService->getInformationJsonId($idEtudiant);
            return $this->jsonSuccess($data);



        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);

        }
    }
    #[Route('/all', name: 'etudiant_show_sans_inscrit', methods: ['GET'])]
    #[TokenRequired]
    public function getEtudiantParIdAll(Request $request): JsonResponse
    {
        try {
            $idEtudiant = $request->query->get('idEtudiant');

            if (!$idEtudiant) {
                return $this->jsonError('Paramètre idEtudiant requis', 400);
            }
            $idEtudiant = (int) $idEtudiant;
           
            $data = $this->etudiantsService->getInformationJsonId($idEtudiant);
            return $this->jsonSuccess($data);



        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/{id}/ecolages', name: 'etudiant_ecolages', methods: ['GET'])]
    public function getEcolages(Etudiants $etudiant): JsonResponse
    {
        try {
            $resultat = $this->etudiantsService->getEcolagesParNiveau($etudiant->getId());
            return $this->json($resultat);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/inscrire', name: 'etudiant_inscrire', methods: ['POST'])]
    #[TokenRequired(['Utilisateur', 'Admin'])]
    public function inscrire(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $requiredFields = ['idEtudiant', 'typeFormation', 'refAdmin', 'dateAdmin', 'montantAdmin', 'refPedag', 'datePedag', 'montantPedag', 'idNiveau', 'idFormation', 'estBoursier'];

            if (isset($data['typeFormation']) && $data['typeFormation'] == "Professionnelle") {
                $requiredFields[] = 'montantEcolage';
                $requiredFields[] = 'refEcolage';
                $requiredFields[] = 'dateEcolage';

            }
            $this->validatorService->validateRequiredFields($data, $requiredFields);

            $idNiveau = $data['idNiveau'];
            $idFormation = $data['idFormation'];
            $user = $this->getUserFromRequest($request);
            $idUser = $user->getId();
            $idEtudiant = $data['idEtudiant'];

            $annee = date('Y');

            $pedagogique = new Payments();
            $montantPedag = $data['montantPedag'];
            $refPedag = $data['refPedag'];
            $datePedagString = $data['datePedag'];
            $datePedag = new \DateTime($datePedagString);
            $pedagogique->setAnatiny($annee, $montantPedag, $refPedag, $datePedag);

            $administratif = new Payments();
            $montantAdmin = $data['montantAdmin'];
            $refAdmin = $data['refAdmin'];
            $dateAdminString = $data['dateAdmin'];
            $dateAdmin = new \DateTime($dateAdminString);
            $administratif->setAnatiny($annee, $montantAdmin, $refAdmin, $dateAdmin);

            $payementEcolage = new Payments();
            $montantEcolage = (float) ($data['montantEcolage'] ?? 0);
            $refEcolage = $data['refEcolage'];
            $dateEcolageString = $data['dateEcolage'];
            $dateEcolage = new \DateTime($dateEcolageString);
            $payementEcolage->setAnatiny($annee, $montantEcolage, $refEcolage, $dateEcolage);

            // Boursier
            $isBoursier = $data['estBoursier'] ?? null;

            $inscription = $this->inscriptionService->inscrireEtudiantId($idEtudiant, $idUser, $pedagogique, $administratif, $payementEcolage, $idNiveau, $idFormation, $isBoursier);
            $details = $this->inscriptionService->getDetailsEtudiantParAnneeId(
                (int) $idEtudiant,
                $annee
            );
            return $this->jsonSuccess($details);



        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }

    }

    #[Route('/niveaux', name: 'etudiant_niveaux', methods: ['GET'])]
    // #[TokenRequired(['Admin'])]
    public function getNiveaux(Request $request): JsonResponse
    {
        try {
            $niveauxClass = $this->niveauEtudiantsService->getAllNiveaux();
            $data = $this->niveauEtudiantsService->transformerArray($niveauxClass);
            return $this->jsonSuccess($data);


        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }

    }

    #[Route('/formations', name: 'etudiant_formations', methods: ['GET'])]
    // #[TokenRequired(['Admin'])]
    public function getFormation(Request $request): JsonResponse
    {
        try {
            // $formationClass = $this->formationEtudiantsService->getAllFormations();
            $formationClass = $this->formationEtudiantsService->findAllFormationExceptIds([5]);
            $resultats = [];
            foreach ($formationClass as $formation) {
                $resultats[] = $formation->toArray();
            }
            return $this->jsonSuccess($resultats);


        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(),  400);
        }

    }

    #[Route('/mentions', name: 'get_mention', methods: ['GET'])]
    // #[TokenRequired(['Admin'])]
    public function getAllMentions(Request $request): JsonResponse
    {
        try {
            // $mentionClass = $this->mentionsService->getAllMentions();
            $mentionClass = $this->mentionsService->getAllMentionsExcept([15, 19,22]);
            $data = $this->mentionsService->transformerArray($mentionClass);
            return $this->jsonSuccess($data);


        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }

    #[Route('/inscrits-par-annee', name: 'etudiants_inscrits_par_annee', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Utilisateur'])]
    public function getEtudiantsInscritsParAnnee(Request $request): JsonResponse
    {
        try {
            $anneeParam = $request->query->get('annee', (new \DateTime())->format('Y'));
            $limit = $request->query->get('limit', null);
            $dateFin = $request->query->get('dateFin', null);

            // Validation de l'année via le service
            $annee = $this->inscriptionService->validerAnnee($anneeParam);


            if ($annee === null) {
                return $this->jsonError('L\'année doit être comprise entre 2000 et 2100', 400);
            }

            // Récupération de la liste via le service
            $etudiants = $this->inscriptionService->getListeEtudiantsInscritsParAnnee($annee, $limit, $dateFin);

            return $this->jsonSuccess($etudiants);

        } catch (\Exception $e) {
            return $this->jsonError('Une erreur est survenue lors de la récupération des étudiants', 500);
        }
    }
    #[TokenRequired(['Admin', 'Utilisateur'])]
    #[Route('/details-par-annee', name: 'etudiant_details_par_annee', methods: ['GET'])]
    public function getDetailsEtudiantParAnnee(Request $request): JsonResponse
    {
        try {
            $idEtudiant = $request->query->get('idEtudiant');
            $anneeParam = $request->query->get('annee', (new \DateTime())->format('Y'));

            // Validation des paramètres
            if (!$idEtudiant) {
                return $this->jsonError('Le paramètre idEtudiant est requis', 400);
            }

            $annee = $this->inscriptionService->validerAnnee($anneeParam);

            if ($annee === null) {
                return $this->jsonError('L\'année doit être comprise entre 2000 et 2100', 400);
            }

            // Récupération des détails via le service
            $details = $this->inscriptionService->getDetailsEtudiantParAnneeId(
                (int) $idEtudiant,
                $annee
            );

            if ($details === null) {
                return $this->jsonError('Étudiant non trouvé ou non inscrit pour cette année '. $idEtudiant, 404);
            }

            return $this->jsonSuccess($details);

        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[TokenRequired(['Admin', 'Utilisateur'])]
    #[Route('/statistiques', name: 'etudiant_statistiques', methods: ['GET'])]
    public function getStatistiquesInscriptions(Request $request): JsonResponse
    {
        try {
            $nbJours = $request->query->get('nbJours', 7);
            $statistiques = $this->inscriptionService->getStatistiquesInscriptions($nbJours);

            return $this->jsonSuccess($statistiques);

        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/niveauParEtudiant', name: 'etudiant_niveau_par_etudiant', methods: ['GET'])]
    // #[TokenRequired(['Admin'])]
    public function getAllNiveauxParEtudiant(Request $request): JsonResponse
    {
        try {
            $idEtudiant = $request->query->get('idEtudiant');
            if (!$idEtudiant) {
                return $this->jsonError('Le paramètre idEtudiant est requis', 400);
            }
            $niveauEtudiants = $this->etudiantsService->getAllNiveauxParEtudiantId($idEtudiant);
            $resultats = [];
            foreach ($niveauEtudiants as $niveauEtudiant) {
                $resultats[] = [
                    'id' => $niveauEtudiant->getId(),
                    'annee' => $niveauEtudiant->getAnnee(),
                ];
            }
            return new JsonResponse([
                'status' => 'success',

                'data' => $resultats
            ], 200);


        } catch (\Exception $e) {
            if ($e->getMessage() === 'Inactif') {
                return $this->jsonError('Utilisateur inactif', 401);
            }

            return $this->jsonError($e->getMessage(), 400);
        }

    }
    #[TokenRequired(['Admin', 'Utilisateur'])]
    #[Route('/save', name: 'etudiant_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        try {
            // Désérialiser la requête en DTO
            $dto = $this->deserializeAndValidate(
                $request,
                EtudiantRequestDto::class,
            );
            
            $etudiantId = $this->etudiantsService->saveEtudiant($dto);

            return $this->json([
                'status' => 'success',
                'message' => $dto->getId() ? 'Étudiant mis à jour avec succès' : 'Étudiant créé avec succès',
                'etudiantId' => $etudiantId
            ]);

        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(),  400);
        }
    }

    #[Route('/{id}/documents', name: 'api_etudiants_get_documents', methods: ['GET'])]
    #[TokenRequired(['Admin', 'Utilisateur'])]
    public function getDocuments(Etudiants $etudiant): JsonResponse
    {
        try {
            // 1. Appel du service pour transformer l'entité en EtudiantResponseDto
            $dto = $this->etudiantsService->getDocumentsDto($etudiant);

            // 2. Retourne le DTO en JSON
            return $this->jsonSuccess($dto);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur lors de la recuperation des documents : ' . $e->getMessage(), 400);
        }
    }

    #[Route('/inscription/update-propos-parents', name: 'etudiant_update_propos_parents', methods: ['POST'])]
    #[TokenRequired(['Admin', 'Utilisateur'])]
    public function updateProposParents(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $idEtudiant = $data['idEtudiant'] ?? null;
            $nomPere = $data['nomPere'] ?? null;
            $nomMere = $data['nomMere'] ?? null;

            if (!$idEtudiant) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Le parametre idEtudiant est requis'
                ], 400);
            }

            $this->etudiantsService->updateProposParents((int) $idEtudiant, $nomPere, $nomMere);

            return $this->jsonSuccess('Filiation mise a jour avec succes');

        } catch (\Exception $e) {
             return $this->jsonError($e->getMessage(),  400);
        }
    }
    #[Route('/changerNiveauEtudiant', name: 'api_changer_niveau', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function changerNiveaux(Request $request): JsonResponse
    {
        try {
             $dto = $this->deserializeAndValidate(
                $request,
                NiveauEtudiantRequestDto::class,
            );            
            $this->etudiantsService->changerNiveauEtudiantDto($dto);


            return $this->jsonSuccess('Niveau mis a jour avec succès');

        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    } 
}