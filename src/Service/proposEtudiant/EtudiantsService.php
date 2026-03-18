<?php

namespace App\Service\proposEtudiant;

use App\Repository\proposEtudiant\EtudiantsRepository;
use App\Repository\proposEtudiant\FormationEtudiantsRepository;
use App\Repository\proposEtudiant\NiveauEtudiantsRepository;
use App\Repository\proposEtudiant\SexesRepository;
use App\Repository\proposEtudiant\FormationsRepository;
use App\Repository\proposEtudiant\MentionsRepository;
use App\Entity\proposEtudiant\Etudiants;
use App\Service\payment\TypeDroitService;
use App\Service\payment\EcolageService;
use App\Service\payment\PaymentService;
use App\Entity\proposEtudiant\Propos;
use App\Dto\proposEtudiant\EtudiantRequestDto;
use App\Dto\proposEtudiant\EtudiantResponseDto;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Entity\payment\Ecolages;
use App\Service\proposEtudiant\mapper\EtudiantMapper;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\proposEtudiant\mapper\InscriptionMapper;
use App\Service\proposEtudiant\ProposService;
use App\Dto\proposEtudiant\NiveauEtudiantRequestDto;

class EtudiantsService
{
    private EtudiantsRepository $etudiantsRepository;
    private EntityManagerInterface $em;
    private FormationEtudiantsService $formationEtudiantsService;
    private NiveauEtudiantsService $niveauEtudiantsService;
    private PaymentService $paymentService;

    private TypeDroitService $typeDroitService;
    private EcolageService $ecolageService;
    private FormationsRepository $formationsRepository;
    private EtudiantMapper $etudiantMapper;

    private ValidatorInterface $validator;

    private InscriptionMapper $inscriptionMapper;
    private ProposService $proposService;
    private MentionsService $mentionsService;

    private StatusEtudiantService $statusEtudiantService;

    public function __construct(
        EtudiantsRepository $etudiantsRepository,
        EntityManagerInterface $em,
        FormationEtudiantsService $formationEtudiantsService,
        NiveauEtudiantsService $niveauEtudiantsService,
        PaymentService $paymentService,
        TypeDroitService $typeDroitService,
        EcolageService $ecolageService,
        EtudiantMapper $etudiantMapper,
        ValidatorInterface $validator,
        InscriptionMapper $inscriptionMapper,
        ProposService $proposService,
        MentionsService $mentionsService,
        StatusEtudiantService $statusEtudiantService
    ) {
        $this->etudiantsRepository = $etudiantsRepository;
        $this->em = $em;
        $this->formationEtudiantsService = $formationEtudiantsService;
        $this->niveauEtudiantsService = $niveauEtudiantsService;
        $this->paymentService = $paymentService;
        $this->typeDroitService = $typeDroitService;
        $this->ecolageService = $ecolageService;
        $this->etudiantMapper = $etudiantMapper;
        $this->validator = $validator;
        $this->inscriptionMapper = $inscriptionMapper;
        $this->proposService = $proposService;
        $this->mentionsService = $mentionsService;
        $this->statusEtudiantService = $statusEtudiantService;
    }

    public function toArray(?Etudiants $etudiant = null): array
    {
        if ($etudiant === null) {
            return [];
        }

        $propos = $this->proposService->getDernierProposByEtudiant($etudiant);
        $nationalite = $etudiant->getNationalite();
        $cin = $etudiant->getCin();
        $bacc = $etudiant->getBacc();

        return [
            'id' => $etudiant->getId(),
            'nom' => $etudiant->getNom(),
            'prenom' => $etudiant->getPrenom(),
            'dateNaissance' => $etudiant->getDateNaissance()
                ? $etudiant->getDateNaissance()->format('Y-m-d')
                : null,
            'lieuNaissance' => $etudiant->getLieuNaissance(),
            'sexe' => $etudiant->getSexe()
                ? $etudiant->getSexe()->getNom()
                : null,
            'contact' => $this->proposService->toArray($propos),
            'nationalite' => $nationalite ? [
                'nom' => $nationalite->getNom(),
                'type' => $nationalite->getType(),
                'typeNationaliteNom' => $nationalite->getTypeNationaliteNom(),
            ] : null,
            'cin' => $cin ? [
                'id' => $cin->getId(),
                'numero' => $cin->getNumero(),
                'dateDelivrance' => $cin->getDateCin() ? $cin->getDateCin()->format('Y-m-d') : null,
                'lieuDelivrance' => $cin->getLieu(),
            ] : null,
            'bacc' => $cin ? $bacc->toArray(): null,

        ];
    }
    public function transformerArray(array $etudiants): array
    { 
        $results=[];

        foreach ($etudiants as $etudiant) {

                $results[] = $this->toArray($etudiant);
            }
        return $results;
    }

    public function rechercheEtudiant($nom, $prenom): ?array
    {
        $nomMajuscule = mb_strtoupper($nom, 'UTF-8');
        $prenom = mb_convert_case($prenom, MB_CASE_TITLE, "UTF-8");

        // throw new Exception($prenom);
        return $this->etudiantsRepository->getEtudiantsByNomAndPrenom($nomMajuscule, $prenom);
    }

    public function rechercheEtudiantExacte($nom, $prenom): ?Etudiants
    {
        $nomMajuscule = mb_strtoupper($nom, 'UTF-8');
        $prenom = mb_convert_case($prenom, MB_CASE_TITLE, "UTF-8");

        // throw new Exception($prenom);
        return $this->etudiantsRepository->getEtudiantsByNomAndPrenomExacte($nomMajuscule, $prenom);
    }

    public function insertEtudiant(Etudiants $etudiant): Etudiants
    {
        $nomMajuscule = mb_strtoupper($etudiant->getNom(), 'UTF-8');
        $prenom = mb_convert_case($etudiant->getPrenom(), MB_CASE_TITLE, "UTF-8");
        $etudiant->setNom($nomMajuscule);
        $etudiant->setPrenom($prenom);
        $this->em->persist($etudiant);
        $this->em->flush();
        return $etudiant;
    }

    public function getEtudiantById(int $id): ?Etudiants
    {
        return $this->etudiantsRepository->find($id);
    }

    public function getEcolagesParNiveau(string $etudiantId): array
    {
        // 1. Récupérer l'étudiant
        $etudiant = $this->etudiantsRepository->find($etudiantId);
        if (!$etudiant) {
            throw new Exception("Étudiant non trouvé");
        }

        // 2. Récupérer la dernière formation de l'étudiant
        $formationEtudiant = $this->formationEtudiantsService->getDernierFormationParEtudiant($etudiant);
        if (!$formationEtudiant) {
            return [
                'status' => 'error',
                'message' => 'Aucune formation trouvée pour cet étudiant'
            ];
        }

        // 3. Récupérer le niveau actuel de l'étudiant
        $niveauEtudiant = $this->niveauEtudiantsService->getDernierNiveauParEtudiant($etudiant);

        if (!$niveauEtudiant || !$niveauEtudiant->getNiveau()) {
            return [
                'status' => 'error',
                'message' => 'Aucun niveau trouvé pour cet étudiant'
            ];
        }

        $niveau = $niveauEtudiant->getNiveau();
        $formation = $formationEtudiant->getFormation();

        // 7. Récupérer les paiements existants
        // $paiements = $this->payementsEcolagesRepository->findPaiementsByEtudiant($etudiant);

        // 9. Préparer la réponse
        return [
            'formation' => [
                'id' => $formation->getId(),
                'nom' => $formation->getNom(),
                'type' => $formation->getTypeFormation() ? $formation->getTypeFormation()->getNom() : null,
                'niveau' => $niveau->getNom()
            ],
        ];
    }
    public function getAllFormationParEtudiantId(int $etudiantId): array
    {
        $etudiant = $this->etudiantsRepository->find($etudiantId);
        if (!$etudiant) {
            throw new Exception("Étudiant non trouvé pour l'ID: " . $etudiantId);
        }
        return $this->formationEtudiantsService->getAllFormationParEtudiant($etudiant);
    }
    public function getAllNiveauxParEtudiantId(int $etudiantId): array
    {
        $etudiant = $this->getOrFailUtilisateur($etudiantId);
        return $this->niveauEtudiantsService->getAllNiveauxParEtudiant($etudiant);
    }
    public function getMontantResteParAnnee(Etudiants $etudiant, Ecolages $ecolage, int $annee): float
    {
        $valiny = 0.0;
        $typeDroit = $this->typeDroitService->getById(3);
        if (!$typeDroit) {
            throw new Exception("Le type droit ecolage non trouvé");
        }
        $ecolageParAnnee = $ecolage ? (float) ($ecolage->getMontant() ?? 0) : 0.0;
        $montantEcolagePayer = $this->paymentService->getSommeMontantByEtudiantTypeAnnee($etudiant, $typeDroit, $annee);
        $valiny = $ecolageParAnnee - $montantEcolagePayer;
        return $valiny;
    }
    public function isValideEcolage(Etudiants $etudiant): void
    {
        
        $listeErreur = [];
        $niveauEtudiants = $this->niveauEtudiantsService->getAllNiveauxParEtudiant($etudiant);
        

        foreach ($niveauEtudiants as $niveauEtudiant) {
            if (!$niveauEtudiant->getNiveau()) {
                continue;
            }
            $formationEtudiant = $this->formationEtudiantsService->findActiveFormationAtDate($etudiant, $niveauEtudiant->getAnnee());
            $idTypeFormationActuelle = $formationEtudiant
                ->getFormation()?->getTypeFormation()?->getId() ?? 1;

            if ($idTypeFormationActuelle == 1) {
                continue;
            }
            $ecolage = $this->ecolageService->getEcolageParFormation($formationEtudiant->getFormation());

            $montantReste = $this->getMontantResteParAnnee($etudiant, $ecolage, $niveauEtudiant->getAnnee());

            if ($montantReste > 0) {
                $listeErreur[] = [
                    'annee' => $niveauEtudiant->getAnnee(),
                    'montant' => $montantReste,
                ];
            }
        }

        // Si des erreurs ont été détectées, on lance une seule exception
        if (!empty($listeErreur)) {
            $erreursTexte = [];
            foreach ($listeErreur as $erreur) {
                $erreursTexte[] = "Année {$erreur['annee']}, montant restant {$erreur['montant']}";
            }

            $message = "Écolages incomplets : " . implode("; ", $erreursTexte);

            throw new Exception($message);
        }

    }

    public function saveEtudiant(EtudiantRequestDto $dto): int
    {
        $this->validateData($dto);

        $this->em->beginTransaction();

        try {
            $etudiant = $this->etudiantMapper->getOrCreateEntity($dto);

            $isNewEtudiant = !$dto->getId();

            $this->etudiantMapper->mapDtoToEntity($dto, $etudiant);
            $nom=$etudiant->getNom();
            $prenom=$etudiant->getPrenom();
            $etudiantTaloha = $this->rechercheEtudiantExacte($nom,$prenom);
            $idEtudiantTaloha = $dto->getId();
            if ($etudiantTaloha&&!$idEtudiantTaloha) {
                throw new Exception("Etudiant existe deja ".$nom." ".$prenom);
            }
            $this->em->persist($etudiant);

            $propos = $this->proposService->getOrCreateEntity($dto);
            $this->proposService->mapDtoToEntity($dto, $propos);
            $propos->setDateInsertion(new DateTime());
            $propos->setEtudiant($etudiant);
            $this->em->persist($propos);
            $this->em->flush();

            
            if ($isNewEtudiant) {
                $this->inscriptionMapper->createInitialInscription($etudiant, $dto);

            }

            $this->em->flush();
            $this->em->commit();

            return $etudiant->getId();

        } catch (Exception $e) {
            if ($this->em->getConnection()->isTransactionActive()) {
                $this->em->rollback();
            }

            // Relancer l'exception avec les détails techniques
            throw $e;
        }
    }

    private function validateData($data): void
    {
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new Exception(json_encode(['errors' => $errorMessages]));
        }
    }


    public function getDocumentsDto(Etudiants $etudiant): EtudiantResponseDto
    {
        $cin = $etudiant->getCin();
        $bacc = $etudiant->getBacc();
        $nationalite = $etudiant->getNationalite();
        $propos = $this->proposService->getDernierProposByEtudiant($etudiant);

        return new EtudiantResponseDto(
            id: $etudiant->getId(),
            nom: $etudiant->getNom(),
            prenom: $etudiant->getPrenom(),
            dateNaissance: $etudiant->getDateNaissance(),
            lieuNaissance: $etudiant->getLieuNaissance(),
            sexeId: $etudiant->getSexe() ? $etudiant->getSexe()->getId() : null,
            cinNumero: $cin ? $cin->getNumero() : null,
            cinLieu: $cin ? $cin->getLieu() : null,
            dateCin: $cin ? $cin->getDateCin() : null,
            baccNumero: $bacc ? $bacc->getNumero() : null,
            baccAnnee: $bacc ? $bacc->getAnnee() : null,
            baccSerie: $bacc ? $bacc->getSerie() : null,
            proposId: $propos ? $propos->getId() : null,
            proposEmail: $propos ? $propos->getEmail() : null,
            proposAdresse: $propos ? $propos->getAdresse() : null,
            proposTelephone: $propos ? $propos->getTelephone() : null,
            nomPere: $propos ? $propos->getNomPere() : null,
            nomMere: $propos ? $propos->getNomMere() : null,
            nationaliteId: $nationalite ? $nationalite->getId() : null
        );
    }

    public function updateProposParents(int $idEtudiant, ?string $nomPere, ?string $nomMere): void
    {
        $etudiant = $this->getOrFailUtilisateur($idEtudiant);

        $propos = $this->proposService->getDernierProposByEtudiant($etudiant);
        if (!$propos) {
            $propos = new Propos();
            $propos->setEtudiant($etudiant);
            $propos->setDateInsertion(new DateTime());
        }

        $propos->setNomPere($nomPere);
        $propos->setNomMere($nomMere);

        $this->em->persist($propos);
        $this->em->flush();
    }
    public function getInformationJson(Etudiants $etudiant = null) : array
    {
        $formationEtudiant = $this->formationEtudiantsService
                ->getDernierFormationParEtudiant($etudiant);

            $niveauActuel = $this->niveauEtudiantsService
                ->getDernierNiveauParEtudiant($etudiant);


            $identite = $this->toArray($etudiant);
            // 1. Initialisation des variables
            $formation = null;
            $typeFormation = null;
            $niveau = null;
            $mention = null;
            $statusEtudiant = null;

            // 2. Récupération des objets si existants
            if ($formationEtudiant) {
                $formation = $formationEtudiant->getFormation();
                $typeFormation = $formation?->getTypeFormation();
            }

            if ($niveauActuel) {
                $niveau = $niveauActuel->getNiveau();
                $mention = $niveauActuel->getMention();
                $statusEtudiant = $niveauActuel->getStatusEtudiant();
            }

            // 3. Construction du tableau
            $formation = [
                'idFormation' => $formation?->getId(),
                'formation' => $formation?->getNom(),
                'formationType' => $typeFormation?->getNom(),

                'idNiveau' => $niveau?->getId(),
                'typeNiveau' => $niveau?->getType(),
                'gradeNiveau' => $niveau?->getGrade(),
                'niveau' => $niveau?->getNom(),

                'idMention' => $mention?->getId(),
                'mention' => $mention?->getNom(),

                'idStatusEtudiant' => $statusEtudiant?->getId(),
                'statusEtudiant' => $statusEtudiant?->getName(),

                'matricule' => $niveauActuel?->getMatricule(),
                'estBoursier' => $niveauActuel?->getIsBoursier(),
                'remarque' => $niveauActuel?->getRemarque(),
                'annee' => $niveauActuel?->getAnnee()
            ];


        return [
            'identite' => $identite,
            'formation' => $formation,
        ];
    }
    public function getInformationJsonId(int $id): array{
        $etudiant = $this->getOrFailUtilisateur($id);
        return $this->getInformationJson($etudiant);
    }
    function getEntityOrFail($service, $method, $id, string $message)
    {
        if (!$id) {
            return null;
        }

        $entity = $service->$method($id);

        if (!$entity) {
            throw new Exception($message . $id);
        }

        return $entity;
    }
    function getOrFailUtilisateur($idEtudiant)
    {
        $result = $this->getEntityOrFail(
            $this->etudiantsRepository,
            'find',
            $idEtudiant,
            'Etudiant non trouve pour id = '
        );
        return $result;
    }
    function getOrFailMention($idMention)
    {
        $result = $this->getEntityOrFail(
            $this->mentionsService,
            'getById',
            $idMention,
            'Mention non trouve pour id = '
        );
        return $result;
    }
    function getOrFailNiveauEtudiant($idNiveauEtudiant)
    {
        $result = $this->getEntityOrFail(
            $this->niveauEtudiantsService,
            'getNiveauxById',
            $idNiveauEtudiant,
            'Niveau etudiant non trouve pour id = '
        );
        return $result;
    }
    function getOrFailStatusEtudiant($idStatusEtudiant)
    {
        $result = $this->getEntityOrFail(
            $this->statusEtudiantService,
            'getById',
            $idStatusEtudiant,
            'Status etudiant non trouve pour id = '
        );
        return $result;
    }
    function getOrFailFormation($idFormation)
    {
        $result = $this->getEntityOrFail(
            $this->formationEtudiantsService,
            'getFormationById',
            $idFormation,
            'Formation non trouve pour id = '
        );
        return $result;
    }
    public function changerNiveauEtudiantId(
        int $idEtudiant,
        ?int $mentionId = null,
        ?int $niveauId = null,
        ?int $statusEtudiantId = null,
        ?bool $nouvelleNiveau = false,
        ?int $formationId = null,
        ?string $remarque = null,
        ?int $annee = null,
        ?bool $isBoursier = null,
        ?\DateTimeInterface $deleteAt = null
    ) {
        $etudiant = $this->getOrFailUtilisateur($idEtudiant);

        $mention = $this->getOrFailMention($mentionId);

        $niveauEtudiant = $this->getOrFailNiveauEtudiant($niveauId);

        $statusEtudiant = $this->getOrFailStatusEtudiant($statusEtudiantId);

        $formation = $this->getOrFailFormation($formationId);

        $this->niveauEtudiantsService->changerMention(
            $etudiant,
            $mention,
            $niveauEtudiant,
            $statusEtudiant,
            $nouvelleNiveau,
            $formation,
            $remarque,
            $annee,
            $isBoursier,
            $deleteAt
        );
    }
    public function changerNiveauEtudiantDto(NiveauEtudiantRequestDto $dto)
    {
        // throw new Exception($dto->getIdMention());
        $this->changerNiveauEtudiantId($dto->getIdEtudiant(),$dto->getIdMention(),$dto->getIdNiveau(),$dto->getIdStatus(),$dto->getNouvelleNiveau(),$dto->getIdFormation(),$dto->getRemarque(),$dto->getAnnee(),$dto->getIsBoursier());
    }
    public function toArrayListeNiveauEtudiants(array $listeNiveauEtudiants): array
    {
        $result = [];

        foreach ($listeNiveauEtudiants as $niveauEtudiant) {

            $etudiant = $niveauEtudiant->getEtudiant();

            $result[] = [
                'identite' => $this->toArray($etudiant),
                'formation' => $this->niveauEtudiantsService->toArray($niveauEtudiant),
            ];
        }

        return $result;
    }

}
