<?php

namespace App\Controller\Api\utilisateurs;


use App\Controller\Api\utils\BaseApiController;
use App\Entity\utilisateurs\Utilisateur;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Annotation\TokenRequired;

#[Route('/utilisateur')]
class UtilisateursController extends BaseApiController
{

    #[Route('', name: 'user', methods: ['GET'])]
    #[TokenRequired(['Admin'])]
    public function getUtilisateur(Request $request): JsonResponse
    {
        try {

            $users = $this->utilisateurService->getAllUsers();

            $data = $this->utilisateurService->transformerArray($users);
            return $this->jsonSuccess($data);

        } catch (\Exception $e) {
                return $this->jsonError($e->getMessage());

        }

    }
    #[Route('/chefMention', name: 'chef_mention', methods: ['GET'])]
    public function getChefMention(): JsonResponse
    {
        try {
            $users = $this->utilisateurService->getAllChefMention();
            $data = $this->utilisateurService->transformerArray($users);
            return $this->jsonSuccess($data);
        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    #[Route('/professeurChefMention', name: 'professeur_chef_mention', methods: ['GET'])]
    public function getProfesseurChefMention(Request $request): JsonResponse
    {
        try {

            $users = $this->utilisateurService->getAllProfesseurChefMention();

            $data = $this->utilisateurService->transformerArray($users);
            return $this->jsonSuccess($data);

        } catch (\Exception $e) {
                return $this->jsonError($e->getMessage());

        }

    }

    #[Route('', name: 'api_utilisateur_create', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['email', 'nom', 'prenom', 'mdp','role'];
        $this->validatorService->validateRequiredFields($data, $requiredFields);

        $user = new Utilisateur();
        $user->setEmail($data['email'])
             ->setNom($data['nom'])
             ->setPrenom($data['prenom']);

        // 🔐 Hashage simple du mot de passe
        $plainPassword = $data['mdp'];
        $user->setMdp($plainPassword);
        $role= $data['role'];
        try {
            $user = $this->utilisateurService->createUser($user,$role);
            $data = $user->toArray();
            return $this->jsonSuccess($data);

        } catch (\Exception $e) {
			return $this->jsonError($e->getMessage(), 400);
		}        
    }

    #[Route('/{id}', name: 'api_utilisateur_get_one', methods: ['GET'])]
    #[TokenRequired(['Admin'])]
    public function getOneUser(int $id): JsonResponse
    {
        $user = $this->utilisateurService->getUserById($id);

        if (!$user) {
         return $this->jsonError('Utilisateur non trouvé', 404);
        }
        $data = $user->toArray();
        return $this->jsonSuccess($data);
        
    }

    #[Route('/{id}', name: 'api_utilisateur_update', methods: ['PUT'])]
    #[TokenRequired(['Admin'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->jsonError('Données invalides ou JSON mal formé', 400);
        }

        try {
            $user = $this->utilisateurService->updateUser($id, $data);
            $data = $user->toArray();
            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
           return $this->jsonError($e->getMessage(),  400);
        }
    }
     



    #[Route('/login', name: 'api_utilisateur_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['email', 'mdp'];
        $this->validatorService->validateRequiredFields($data, $requiredFields);
        $email = $data['email'];
        $plainPassword = $data['mdp'];

        // 🔑 Vérification du login via le repository
        $user = $this->utilisateurService->login($email, $plainPassword);

        
        if (!$user) {
            return $this->jsonError('Identifiants invalides',  404);
        }
        
        $user_status= $user->getStatus()->getName();
        if ($user_status==="Inactif") {
            return $this->jsonError('Utilisateur inactif',  401);
        }
        $claims = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()->getName(),
            'name' => $user->getNom().' '.$user->getPrenom()
        ];

        $tokenDuration = $this->params->get('jwt_token_duration');

        $token = $this->jwtManager->createToken($claims, $tokenDuration);
        $tokenString = $token->toString();
        $data = [
            'membre' => [
                // 'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()->getName(), // ajouter role ici
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ],
            'token' => $tokenString
        ];
        return $this->jsonSuccess($data);
    }
}
