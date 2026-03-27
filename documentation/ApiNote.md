

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


# Pour avoir les resultats notes par etudiants

**Url:** `GET /notes/resultats/10409?idSemestre=1`

**Header:** `Content-Type: application/json`
    
**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:**`Admin,Utilisateur`

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "type": "Normale",
            "notesListes": [
                {
                    "ue": "Mathematique de l'ingenieur",
                    "notes": [
                        {
                            "matiere": "Algebre",
                            "coefficient": 3,
                            "note": 9,
                            "noteAvecCoefficient": 27
                        }
                    ],
                    "isValid": false,
                    "sommeCoefficients": 3,
                    "sommeNotesAvecCoefficient": 27,
                    "moyenne": 9
                }
            ],
            "moyenne": 9
        },
        {
            "type": "Rattrapage",
            "notesListes": [
                {
                    "ue": "Mathematique de l'ingenieur",
                    "notes": [
                        {
                            "matiere": "Algebre",
                            "coefficient": 3,
                            "note": 10,
                            "noteAvecCoefficient": 30
                        }
                    ],
                    "isValid": true,
                    "sommeCoefficients": 3,
                    "sommeNotesAvecCoefficient": 30,
                    "moyenne": 10
                }
            ],
            "moyenne": 10
        },
        {
            "type": "Final",
            "notesListes": [
                {
                    "ue": "Mathematique de l'ingenieur",
                    "notes": [
                        {
                            "matiere": "Algebre",
                            "coefficient": 3,
                            "note": 10,
                            "noteAvecCoefficient": 30
                        }
                    ],
                    "isValid": true,
                    "sommeCoefficients": 3,
                    "sommeNotesAvecCoefficient": 30,
                    "moyenne": 10
                }
            ],
            "moyenne": 10
        }
    ]
}
```

### Erreur de validation

```json
{
    "status": "error",
    "message": "Erreur de validation : idMatiere : L'idMatiere est obligatoire. | idMention : L'idMention est obligatoire. | credit : Le credit est obligatoire. | coefficient : Le coefficient est obligatoire. | idNiveau : L'idNiveau est obligatoire. | idProfesseur : L'idProfesseur est obligatoire."
}
```

```

# Pour mettre à jour une UE

**Url:** `PUT /notes/ue/{id}`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:** `Admin`

**Body:**

```json
{
    "name": "Mathématiques Appliquées"
}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "name": "Mathématiques Appliquées",
        "id": 1
    }
}
```

# Pour mettre à jour une matière

**Url:** `PUT /notes/matieres/{id}`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:** `Admin`

**Body:**

```json
{
    "name": "Algèbre Linéaire",
    "semestreId": 1,
    "ueId": 1
}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "name": "Algèbre Linéaire",
        "id": 1,
        "ue": {
            "name": "Mathématiques Appliquées",
            "id": 1
        },
        "semestre": {
            "grade": 1,
            "name": "Semestre 1",
            "id": 1
        }
    }
}
```

# Pour mettre à jour une matière coefficient

**Url:** `PUT /notes/matieres-coeff/{id}`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:** `Admin, ChefMention`

**Body:**

```json
{
    "idMatiere": 1,
    "idMention": 1,
    "idNiveau": 1,
    "idProfesseur": 1,
    "credit": 3,
    "coefficient": 3
}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "coefficient": 3,
        "credit": 3
    }
}
```

# Pour assigner un chef de mention

**Url:** `PUT /etudiants/mentions/{mentionId}/chef`

**Header:** `Content-Type: application/json`

**Authorization:** `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzczMjk4MTg2LjE2NjQ4OSwiZXhwIjoxNzczMzAxNzg2LjE2NjQ4OSwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJub20iOiJBZG1pbiIsInByZW5vbSI6IkFkbWluIiwiYWRyZXNzZSI6IkFua2F0c28gLCBwb3J0ZSAxMDQiLCJpZCI6MSwicm9sZSI6IkFkbWluIn0.qaMEC_5W3hgEU5fnavlRuzfZFViP22dZ-CPppZRvDjE`

**Role:** `Admin`

**Body:**

```json
{
    "chefId": 1
}
```

> L'utilisateur désigné doit avoir le rôle `ChefMention` (role id = 4), sinon une erreur est retournée.

**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "nom": "BATIMENT ET TRAVAUX PUBLICS",
        "abr": "BTP",
        "chefMentionId": 1,
        "chefMentionNom": "admin",
        "chefMentionPrenom": "admin"
    }
}
```

### Erreur si l'utilisateur n'est pas ChefMention

```json
{
    "status": "error",
    "message": "L'utilisateur doit être un chef de mention"
}
```

# Pour avoir la liste des chefs de mention

**Url:** `GET /utilisateur/chefMention`

**Header:** `Content-Type: application/json`

**Authorization:** `Bearer <token>`

**Role:** `Tous (token requis)`

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 2,
            "nom": "RAKOTO",
            "prenom": "Jean",
            "email": "jean.rakoto@espa.mg",
            "role": "ChefMention",
            "status": "Actif"
        }
    ]
}
```

# Pour avoir le dasboard des utilisateurs

**Url:** `GET /utilisateur/dasboard`

**Header:** `Content-Type: application/json`

**Authorization:** `Bearer <token>`

**Role:** `Tous (token requis)`

**Response:**
```json
{
    "status": "success",
    "data": {
        "Utilisateur": [
            {
                "nom": "Rakotonandrasana",
                "prenom": "Edinho",
                "roleNom": "Utilisateur",
                "id": 3
            },
            {
                "nom": "RAKOTOVAO RADO",
                "prenom": "Mazavason Firmin",
                "roleNom": "Utilisateur",
                "id": 4
            },
            {
                "nom": "ANDRIAMARORANTO",
                "prenom": "Fenosoa Harivelo",
                "roleNom": "Utilisateur",
                "id": 5
            },
            {
                "nom": "NOROSON",
                "prenom": "Njakarimanana",
                "roleNom": "Utilisateur",
                "id": 6
            },
            {
                "nom": "LARISSA",
                "prenom": "Nomenjanahary",
                "roleNom": "Utilisateur",
                "id": 8
            },
            {
                "nom": "RANDRIAMBOAVAHY",
                "prenom": "Rindraniaina",
                "roleNom": "Utilisateur",
                "id": 12
            },
            {
                "nom": "ROVANIAINA ",
                "prenom": "Jordan",
                "roleNom": "Utilisateur",
                "id": 7
            },
            {
                "nom": "Testeur",
                "prenom": "Testeur",
                "roleNom": "Utilisateur",
                "id": 9
            }
        ],
        "Ecolage": [
            {
                "nom": "ecolage",
                "prenom": "ecolage",
                "roleNom": "Ecolage",
                "id": 10
            },
            {
                "nom": "RAZAFINTSALAMA",
                "prenom": "Hantanirina Tahinasoa",
                "roleNom": "Ecolage",
                "id": 13
            },
            {
                "nom": "RAZAFINTSALAMA",
                "prenom": "Hantanirina Tahinasoa",
                "roleNom": "Ecolage",
                "id": 11
            },
            {
                "nom": "dode",
                "prenom": "ecolagedode",
                "roleNom": "Ecolage",
                "id": 14
            },
            {
                "nom": "ecolage",
                "prenom": "ecolage",
                "roleNom": "Ecolage",
                "id": 2
            }
        ],
        "Admin": [
            {
                "nom": "admin",
                "prenom": "admin",
                "roleNom": "Admin",
                "id": 1
            }
        ],
        "ChefMention": [
            {
                "nom": "Chef Mention",
                "prenom": "Dode",
                "roleNom": "ChefMention",
                "id": 15
            }
        ],
        "Professeur": [
            {
                "nom": "professeur",
                "prenom": "prof",
                "roleNom": "Professeur",
                "id": 16
            }
        ]
    }
}
```
