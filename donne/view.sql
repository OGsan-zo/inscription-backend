drop view if exists vue_notes_etudiants;
CREATE OR REPLACE VIEW vue_notes_etudiants AS
SELECT 
    n.id ,
    n.valeur,
    n.type_note_id,
    tn.name AS type_note_name,
    n.matiere_mention_coefficient_id,
    n.annee,

    e.id AS etudiant_id,
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

drop view if exists vue_dernieres_notes;
CREATE OR REPLACE VIEW vue_dernieres_notes AS
SELECT DISTINCT ON (n.etudiant_id, n.matiere_mention_coefficient_id, n.type_note_id, n.annee)
    n.id,
    n.etudiant_id,
    n.matiere_mention_coefficient_id,
    n.type_note_id,
    n.valeur,
    n.date_validation,
    n.created_at,
    n.deleted_at,
    n.annee
FROM notes n
ORDER BY n.etudiant_id, n.matiere_mention_coefficient_id, n.type_note_id, n.annee, n.date_validation DESC, n.created_at DESC;

drop view if exists vue_niveau_etudiants_details;
create view vue_niveau_etudiants_details as
select
    ne.id,
    e.nom, 
    e.prenom, 
    e.id as etudiant_id,
    ne.niveau_id,
    ne.mention_id,
    ne.date_insertion as created_at,
    ne.deleted_at,
    ne.annee
from niveau_etudiants ne
join etudiants e on ne.etudiant_id = e.id;