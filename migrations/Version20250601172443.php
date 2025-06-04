<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601172443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE academic_info (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, institution_type VARCHAR(100) NOT NULL, current_level VARCHAR(50) NOT NULL, field VARCHAR(100) NOT NULL, average DOUBLE PRECISION DEFAULT NULL, baccalaureate_year INT DEFAULT NULL, UNIQUE INDEX UNIQ_5751A541A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info ADD CONSTRAINT FK_5751A541A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE academic_info DROP FOREIGN KEY FK_5751A541A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE academic_info
        SQL);
    }
}
