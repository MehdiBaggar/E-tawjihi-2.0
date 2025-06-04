<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601182545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info CHANGE niveau_etudes niveau_etudes VARCHAR(50) DEFAULT NULL, CHANGE type_etablissement type_etablissement VARCHAR(100) DEFAULT NULL, CHANGE filiere filiere VARCHAR(100) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info CHANGE type_etablissement type_etablissement VARCHAR(100) NOT NULL, CHANGE niveau_etudes niveau_etudes VARCHAR(50) NOT NULL, CHANGE filiere filiere VARCHAR(100) NOT NULL
        SQL);
    }
}
