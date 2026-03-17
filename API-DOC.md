# API-DOC — Endpoints ESPA Inscription Backend

> Base URL : `http://localhost:8000`
> Toutes les requêtes protégées nécessitent : `Authorization: Bearer <token>`

---

## Authentification

| Méthode | Route | Auth | Description |
|---|---|---|---|
| POST | `/utilisateur/login` | Non | Connexion, retourne le token JWT |
| GET | `/utilisateur` | Admin | Liste tous les utilisateurs |

---

## Étudiants

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/etudiants` | Oui | Liste tous les étudiants |
| GET | `/etudiants/{id}` | Oui | Détail d'un étudiant |
| POST | `/etudiants` | Admin | Créer un étudiant |
| PUT | `/etudiants/{id}` | Admin | Modifier un étudiant |
| DELETE | `/etudiants/{id}` | Admin | Supprimer un étudiant |

---

## Pré-inscription

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/pre-inscription` | Oui | Liste les pré-inscriptions |
| POST | `/pre-inscription` | Oui | Créer une pré-inscription |
| PUT | `/pre-inscription/{id}` | Admin | Modifier une pré-inscription |
| DELETE | `/pre-inscription/{id}` | Admin | Supprimer une pré-inscription |

---

## Écolage (Paiements)

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/ecolage` | Oui | Liste les paiements |
| POST | `/ecolage` | Admin | Enregistrer un paiement |
| GET | `/ecolage/{id}` | Oui | Détail d'un paiement |

---

## Filtres

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/filtres/mentions` | Oui | Liste des mentions |
| GET | `/filtres/niveaux` | Oui | Liste des niveaux |
| GET | `/filtres/formations` | Oui | Liste des formations |
| GET | `/filtres/etudiant` | Oui | Liste des étudiants inscrits (filtrables) |
| GET | `/filtres/etudiant/export` | Oui | Export de la liste des étudiants |

### GET `/filtres/etudiant`

**Query params :**
```
idMention  (int) — optionnel
idNiveau   (int) — optionnel
idParcours (int) — optionnel
```

**Réponse :**
```json
{
  "status": "success",
  "data": [
    {
      "id": 5,
      "nom": "RAKOTO",
      "prenom": "Jean",
      "mention": "Informatique",
      "mentionAbr": "Info",
      "idMention": 1,
      "niveau": "L1",
      "idNiveau": 1,
      "matricule": "2024-INFO-001",
      "dateInsertion": "2024-09-01 08:00:00",
      "idParcours": 2,
      "nomParcours": "Génie Logiciel"
    }
  ]
}
```

> `idParcours` et `nomParcours` sont `null` si aucun parcours n'est encore assigné à l'étudiant.

### GET `/filtres/etudiant/export`

Mêmes query params que `/filtres/etudiant` + `limit` (int, défaut 10000).

---

## Nationalités

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/nationalites` | Oui | Liste des nationalités |

---

## Parcours

### CRUD Parcours

| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/parcours` | Oui | Liste tous les parcours (filtrage côté frontend) |
| GET | `/parcours/{id}` | Oui | Détail d'un parcours |
| POST | `/parcours` | Admin | Créer un parcours |
| PUT | `/parcours/{id}` | Admin | Modifier (soft-delete + recréation) |
| DELETE | `/parcours/{id}` | Admin | Supprimer (soft-delete) |

### Affectation

| Méthode | Route | Auth | Description |
|---|---|---|---|
| POST | `/parcours/assigner` | Admin | Assigner un parcours à plusieurs étudiants |

---

## Détail des endpoints Parcours

### GET `/parcours`
Retourne tous les parcours avec mention et niveau embarqués. Le filtrage par mention/niveau se fait côté frontend.

**Réponse :**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "nom": "Algorithmique",
      "createdAt": "2026-03-17 10:00:00",
      "mention": { "id": 1, "nom": "Informatique", "abr": "Info" },
      "niveau":  { "id": 1, "nom": "L1", "grade": 1 }
    }
  ]
}
```

---

### POST `/parcours/assigner`
Assigne un parcours à une liste d'étudiants (par leur `idNiveauEtudiant`).

**Body JSON :**
```json
{
  "idParcours": 2,
  "idEtudiants": [10, 11, 12, 15]
}
```

**Réponse :**
```json
{
  "status": "success",
  "data": [
    { "idNiveauEtudiant": 10, "idEtudiant": 5 },
    { "idNiveauEtudiant": 11, "idEtudiant": 6 }
  ]
}
```

---

### POST `/parcours`
Créer un nouveau parcours.

**Body JSON :**
```json
{
  "nom": "Algorithmique",
  "idMention": 1,
  "idNiveau": 1
}
```
Reponse

```json
{
    "status": "success",
    "data": {
        "nom": "Algorithmique",
        "id": 1,
        "createdAt": "2026-03-17 20:30:39",
        "mention": {
            "id": 1,
            "nom": "BATIMENT ET TRAVAUX PUBLICS",
            "abr": "BTP"
        },
        "niveau": {
            "id": 1,
            "nom": "L1",
            "grade": 1
        }
    }
}
```

---

### PUT `/parcours/{id}`
Soft-delete l'ancien parcours et crée un nouveau avec les nouvelles valeurs.

**Body JSON :**
```json
{
  "nom": "Algorithmique avancée",
  "idMention": 1,
  "idNiveau": 2
}
```

---

## Format des réponses

### Succès
```json
{ "status": "success", "data": { ... } }
{ "status": "success", "data": [ ... ] }
```

### Erreur
```json
{ "status": "error", "message": "Description de l'erreur" }
```

---

## Migrations à lancer

Après ajout de la FK `parcours` dans `NiveauEtudiants` et les colonnes `createdAt`/`deletedAt`/`niveau_id` dans `Parcours` :

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
