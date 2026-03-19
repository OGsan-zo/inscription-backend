<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260319123229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Conversion de grade (string -> integer) avec gestion du DEFAULT';
    }

    public function up(Schema $schema): void
    {
        // 1. Supprimer le DEFAULT existant (important sinon erreur)
        $this->addSql('ALTER TABLE semestres ALTER COLUMN grade DROP DEFAULT');

        // 2. Convertir la colonne de string vers integer
        $this->addSql("
            ALTER TABLE semestres 
            ALTER COLUMN grade TYPE INTEGER 
            USING (
                CASE 
                    WHEN grade = 'L1' THEN 1
                    WHEN grade = 'L2' THEN 2
                    WHEN grade = 'L3' THEN 3
                    WHEN grade = 'M1' THEN 4
                    WHEN grade = 'M2' THEN 5
                    ELSE NULL
                END
            )
        ");

        // 3. (Optionnel) définir une valeur par défaut
        $this->addSql('ALTER TABLE semestres ALTER COLUMN grade SET DEFAULT 1');

        // 4. Rendre la colonne NOT NULL (si requis)
        $this->addSql('ALTER TABLE semestres ALTER COLUMN grade SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // 1. Supprimer le DEFAULT integer
        $this->addSql('ALTER TABLE semestres ALTER COLUMN grade DROP DEFAULT');

        // 2. Reconvertir integer -> string
        $this->addSql("
            ALTER TABLE semestres 
            ALTER COLUMN grade TYPE VARCHAR(50)
            USING (
                CASE 
                    WHEN grade = 1 THEN 'L1'
                    WHEN grade = 2 THEN 'L2'
                    WHEN grade = 3 THEN 'L3'
                    WHEN grade = 4 THEN 'M1'
                    WHEN grade = 5 THEN 'M2'
                    ELSE NULL
                END
            )
        ");

        // 3. Remettre un DEFAULT string
        $this->addSql("ALTER TABLE semestres ALTER COLUMN grade SET DEFAULT 'L1'");

        // 4. Rendre nullable (comme avant)
        $this->addSql('ALTER TABLE semestres ALTER COLUMN grade DROP NOT NULL');
    }
}