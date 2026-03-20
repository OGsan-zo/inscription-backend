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

drop view if exists vue_matiere_coeff_detail;
CREATE OR REPLACE VIEW vue_matiere_coeff_detail AS
SELECT 
    mmc.id,
    mmc.coefficient,

    -- Matière
    m.id AS matiere_id,
    m.name AS matiere_nom,

    -- Semestre
    s.id AS semestre_id,
    s.name AS semestre_nom,

    -- Mention
    me.id AS mention_id,
    me.nom AS mention_nom,

    -- Niveau
    n.id AS niveau_id,
    n.nom AS niveau_nom,

    -- Professeur
    u.id AS professeur_id,
    u.nom AS professeur_nom,
    u.prenom AS professeur_prenom,

    -- Meta
    mmc.created_at,
    mmc.deleted_at

FROM matiere_mention_coefficient mmc

JOIN matieres m 
    ON mmc.matiere_id = m.id

JOIN semestres s 
    ON m.semestre_id = s.id

JOIN mentions me 
    ON mmc.mention_id = me.id

JOIN niveaux n 
    ON mmc.niveau_id = n.id

JOIN utilisateur u 
    ON mmc.professeur_id = u.id;

--- SELECT * FROM vue_matiere_coeff_detail;
