<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427005749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_year_branch (academic_year_id INT NOT NULL, branch_id INT NOT NULL, INDEX IDX_6F78A563C54F3401 (academic_year_id), INDEX IDX_6F78A563DCD6CC49 (branch_id), PRIMARY KEY(academic_year_id, branch_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE academic_year_branch ADD CONSTRAINT FK_6F78A563C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE academic_year_branch ADD CONSTRAINT FK_6F78A563DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academic_year_branch DROP FOREIGN KEY FK_6F78A563C54F3401');
        $this->addSql('ALTER TABLE academic_year_branch DROP FOREIGN KEY FK_6F78A563DCD6CC49');
        $this->addSql('DROP TABLE academic_year_branch');
    }
}
