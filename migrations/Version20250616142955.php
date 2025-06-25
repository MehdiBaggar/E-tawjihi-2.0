<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616142955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE resultats_tests CHANGE test_id test_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tests ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tests ADD CONSTRAINT FK_1260FC5EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1260FC5EA76ED395 ON tests (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE resultats_tests CHANGE test_id test_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tests DROP FOREIGN KEY FK_1260FC5EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1260FC5EA76ED395 ON tests
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tests DROP user_id
        SQL);
    }
}
