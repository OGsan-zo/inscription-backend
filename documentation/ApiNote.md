

# Pour avoir les UE

**Url:** `GET /notes/ue`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "name": "Mathematique de l'ingenieur",
            "id": 1
        }
    ]
}
```

# Pour creer une nouvelle UE

**Url:** `POST /notes/ue`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin`

**Body:**

```json
{
    "name":"Mathematique de l'ingenieur"
}
```
**Response:**
```json
{
    "status": "success",
    "data": {
        "name": "Mathematique de l'ingenieur",
        "id": 1
    }
}
```
# Pour avoir les UE

**Url:** `GET /notes/ue`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "name": "Mathematique de l'ingenieur",
            "id": 1
        }
    ]
}
```

# Pour avoir les semestres

**Url:** `GET /notes/semestres`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "grade": 1,
            "name": "Semestre 1",
            "id": 1
        },
        {
            "grade": 2,
            "name": "Semestre 2",
            "id": 2
        },
        {
            "grade": 3,
            "name": "Semestre 3",
            "id": 3
        },
        {
            "grade": 4,
            "name": "Semestre 4",
            "id": 4
        },
        {
            "grade": 5,
            "name": "Semestre 5",
            "id": 5
        },
        {
            "grade": 6,
            "name": "Semestre 6",
            "id": 6
        },
        {
            "grade": 7,
            "name": "Semestre 7",
            "id": 7
        },
        {
            "grade": 8,
            "name": "Semestre 8",
            "id": 8
        },
        {
            "grade": 9,
            "name": "Semestre 9",
            "id": 9
        },
        {
            "grade": 10,
            "name": "Semestre 10",
            "id": 10
        }
    ]
}
```

# Pour creer une nouvelle UE

**Url:** `POST /notes/ue`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin`

**Body:**

```json
{
    "name":"Algebre",
    "semestreId":1,
    "ueId":1
}
```
**Response:**
```json
{
    "status": "success",
    "data": {
            "name": "Algebre",
            "id": 1
    }
}
```

# Pour avoir les matieres

**Url:** `GET /notes/semestres`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "name": "Algebre",
            "id": 1,
            "ue": {
                "name": "Mathematique de l'ingenieur",
                "id": 1
            },
            "semestre": {
                "grade": 1,
                "name": "Semestre 1",
                "id": 1
            }
        }
    ]
}
```

# Pour avoir les matieres coefficient

**Url:** `GET /notes/matieres-coeff`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "coefficient": 3,
            "matiereId": 1,
            "matiereNom": "Algebre",
            "semestreId": 1,
            "semestreNom": "Semestre 1",
            "mentionId": 1,
            "mentionNom": "BATIMENT ET TRAVAUX PUBLICS",
            "niveauId": 1,
            "niveauNom": "L1",
            "professeurId": 1,
            "professeurNom": "admin",
            "professeurPrenom": "admin",
            "id": 1
        }
    ]
}
```

# Pour creer une nouvelle matiere coefficient

**Url:** `POST /notes/matiere-coeff`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`ChefMention`

**Body:**

```json
{
    "idMatiere":1,
    "idMention":1,
    "idNiveau":1,
    "idProfesseur":1,
    "coefficient":3
}
```
**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "matiere": {
            "id": 1,
            "name": "Algebre"
        },
        "semestre": {
            "id": 1,
            "name": "Semestre 1"
        },
        "mention": {
            "id": 1,
            "nom": "BATIMENT ET TRAVAUX PUBLICS",
            "abr": "BTP"
        },
        "coefficient": 3,
        "niveau": {
            "id": 1,
            "nom": "L1",
            "type": 1,
            "grade": 1
        },
        "professeur": {
            "id": 1,
            "nom": "admin",
            "prenom": "admin",
            "email": "admin@gmail.com"
        }
    }
}
```


# Pour avoir les matieres coefficient

**Url:** `GET //notes/matieres-coeff/etudiant/1?annee=2026`

**Header:** `Content-Type: application/json`
    
**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`ChefMention , Admin`

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "valeur": "15.50",
            "typeNoteId": 1,
            "typeNoteName": "Normal",
            "matiereMentionCoefficientId": 1,
            "etudiantId": 1,
            "nom": "MENJANIAINA",
            "prenom": "Aurelia",
            "annee": 2026,
            "dateValidation": "2026-03-20 10:00:00",
            "id": 4,
            "createdAt": "2026-03-20 11:36:07"
        },
        {
            "valeur": "12.00",
            "typeNoteId": 2,
            "typeNoteName": "Rattrapage",
            "matiereMentionCoefficientId": 1,
            "etudiantId": 2,
            "nom": "RASOAMAMPIONINA",
            "prenom": "Stalone",
            "annee": 2026,
            "dateValidation": "2026-03-20 11:00:00",
            "id": 5,
            "createdAt": "2026-03-20 11:36:07"
        }
    ]
}
```

# Pour valider une note

**Url:** `PUT /notes/valider/1`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin,ChefMention`

**Body:**


**Response:**
```json
{
    "status": "success",
    "data": {
        "valeur": "15.50",
        "dateValidation": "2026-03-20 10:48:33",
        "id": 1
    }
}
```

# Pour avoir les matieres coefficient pour chaque proffesseur

**Url:** `GET /notes/matieres-coeff/professeur`

**Header:** `Content-Type: application/json`
    
**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin,Professeur`

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "coefficient": 3,
            "matiereId": 1,
            "matiereNom": "Algebre",
            "semestreId": 1,
            "semestreNom": "Semestre 1",
            "mentionId": 1,
            "mentionNom": "BATIMENT ET TRAVAUX PUBLICS",
            "niveauId": 1,
            "niveauNom": "L1",
            "professeurId": 1,
            "professeurNom": "admin",
            "professeurPrenom": "admin",
            "id": 1
        }
    ]
}
```

# Pour avoir les professeur et chef de mention

**Url:** `GET /utilisateur/professeurChefMention`

**Header:** `Content-Type: application/json`
    

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nom": "admin",
            "prenom": "admin",
            "email": "admin@gmail.com",
            "role": "Admin",
            "status": "Actif"
        }
    ]
}
```
# Pour avoir les notes etudiant a chaque proffeseur

**Url:** `GET /notes/matieres-coeff/professeur/1?annee=2026`

**Header:** `Content-Type: application/json`
    
**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Professeur , Admin`

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "details": {
                "etudiantId": 10064,
                "nom": "ANDRIATAHIRY",
                "prenom": "Rafanomezantsoa Henri",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10180,
                "nom": "BENAH",
                "prenom": "Ny Aina Malala Irimanjaka",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10190,
                "nom": "RAKOTONIRINA",
                "prenom": "Antsotiana Kôvenue",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10173,
                "nom": "ANDRIAMANANTENA",
                "prenom": "Ionty",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10202,
                "nom": "TOLOTRANDRIANINA",
                "prenom": "Fanirina Fiaro",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10200,
                "nom": "RAZAFIMAHATRATRA",
                "prenom": "Nieferana Toela",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10175,
                "nom": "ANDRIAMIHARINJARA",
                "prenom": "Lucero Médicis",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10194,
                "nom": "RANDRIAMALALA",
                "prenom": "Andriana Mahery",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10199,
                "nom": "RATOVOHARIMIANDRA",
                "prenom": "Valimpitia Andresy",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10182,
                "nom": "LANTOHARINIAINA",
                "prenom": "Kaloina Iriantsoa Hendritiana",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10192,
                "nom": "RAMAROSONANJARA",
                "prenom": "Mihaja Ikoriantsoa",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10172,
                "nom": "ANDRIAMAMPITA",
                "prenom": "Tsiory",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10184,
                "nom": "RABARIVOLA",
                "prenom": "Harimalala Mihantaniaina",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10195,
                "nom": "RANDRIAMIHAJARISON",
                "prenom": "Miharisoa Faniry",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10177,
                "nom": "ANDRIAMIHARISOA",
                "prenom": "Marie Rindra",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10193,
                "nom": "RAMASIARITIANA",
                "prenom": "Andriniaina Judicaël",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": [
                {
                    "etudiantId": 10193,
                    "matiereMentionCoefficientId": 1,
                    "typeNoteId": 1,
                    "valeur": 16.5,
                    "annee": 2026,
                    "dateValidation": "2026-03-20 10:00:00"
                },
                {
                    "etudiantId": 10193,
                    "matiereMentionCoefficientId": 1,
                    "typeNoteId": 2,
                    "valeur": 11,
                    "annee": 2026,
                    "dateValidation": "2026-03-20 11:00:00"
                }
            ]
        },
        {
            "details": {
                "etudiantId": 10188,
                "nom": "RAKOTOARIMANANA",
                "prenom": "Miharisoa Rado",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        },
        {
            "details": {
                "etudiantId": 10178,
                "nom": "ANDRIAVELOJAONA",
                "prenom": "Miranto Princy",
                "niveauId": 1,
                "mentionId": 1,
                "annee": 2026
            },
            "notes": []
        }
    ]
}
```

# Pour inserer des notes sur professeur

**Url:** `POST /notes/matieres-coeff/professeur`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin,Professeur`

**Body:**

```json
{
  "idMatiereCoefficient": 1,
  "annee": 2026,
  "isNormale": true,
  "listeEtudiants": [
    {
      "etudiantId": 10064,
      "valeur": 15.5
    },
    {
      "etudiantId": 10180,
      "valeur": 12.0
    }
  ]
}
```
**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "valeur": "16",
            "annee": 2026,
            "dateValidation": null
        }
    ]
}
```