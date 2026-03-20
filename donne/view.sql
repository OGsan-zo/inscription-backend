CREATE OR REPLACE VIEW vue_notes_etudiants AS
SELECT 
    n.id ,
    n.valeur,
    n.type_note_id,
    tn.name AS type_note_name,
    n.matiere_mention_coefficient_id,
    n.annee,

    e.id AS id_etudiant,
    e.nom,
    e.prenom,
    n.date_validation,
    n.created_at,
    n.deleted_at
    

FROM notes n
JOIN etudiants e 
    ON n.etudiant_id = e.id

JOIN type_notes tn 
    ON n.type_note_id = tn.id;

-- SELECT * FROM vue_notes_etudiants;