-- Exemple : insérer 5 notes
INSERT INTO notes
    (etudiant_id, matiere_mention_coefficient_id, type_note_id, created_at, deleted_at, date_validation, valeur,annee)
VALUES
    (1, 1, 1, NOW(), NULL, '2026-03-20 10:00:00', 15.50,2026),
    (2, 1, 2, NOW(), NULL, '2026-03-20 11:00:00', 12.00,2026);


INSERT INTO notes
    (etudiant_id, matiere_mention_coefficient_id, type_note_id, created_at, deleted_at, date_validation, valeur,annee)
VALUES
    (1, 1, 1, NOW(), NULL, '2026-03-20 10:00:00', 16.50,2026),
    (2, 1, 2, NOW(), NULL, '2026-03-20 11:00:00', 11.00,2026);
