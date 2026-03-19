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




