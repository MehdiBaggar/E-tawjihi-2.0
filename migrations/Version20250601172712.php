<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601172712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info ADD type_etablissement VARCHAR(100) NOT NULL, ADD filiere VARCHAR(100) NOT NULL, DROP institution_type, DROP field, CHANGE current_level niveau_etudes VARCHAR(50) NOT NULL, CHANGE average moyenne_generale DOUBLE PRECISION DEFAULT NULL, CHANGE baccalaureate_year annee_obtention_bac INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info ADD institution_type VARCHAR(100) NOT NULL, ADD field VARCHAR(100) NOT NULL, DROP type_etablissement, DROP filiere, CHANGE niveau_etudes current_level VARCHAR(50) NOT NULL, CHANGE moyenne_generale average DOUBLE PRECISION DEFAULT NULL, CHANGE annee_obtention_bac baccalaureate_year INT DEFAULT NULL
        SQL);
    }
}
