<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430213935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_academic_year (group_id INT NOT NULL, academic_year_id INT NOT NULL, INDEX IDX_78B32963FE54D947 (group_id), INDEX IDX_78B32963C54F3401 (academic_year_id), PRIMARY KEY(group_id, academic_year_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_branch (group_id INT NOT NULL, branch_id INT NOT NULL, INDEX IDX_6800C3CFFE54D947 (group_id), INDEX IDX_6800C3CFDCD6CC49 (branch_id), PRIMARY KEY(group_id, branch_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_academic_year ADD CONSTRAINT FK_78B32963FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_academic_year ADD CONSTRAINT FK_78B32963C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_branch ADD CONSTRAINT FK_6800C3CFFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_branch ADD CONSTRAINT FK_6800C3CFDCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classroom ADD available TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_academic_year DROP FOREIGN KEY FK_78B32963FE54D947');
        $this->addSql('ALTER TABLE group_academic_year DROP FOREIGN KEY FK_78B32963C54F3401');
        $this->addSql('ALTER TABLE group_branch DROP FOREIGN KEY FK_6800C3CFFE54D947');
        $this->addSql('ALTER TABLE group_branch DROP FOREIGN KEY FK_6800C3CFDCD6CC49');
        $this->addSql('DROP TABLE group_academic_year');
        $this->addSql('DROP TABLE group_branch');
        $this->addSql('ALTER TABLE classroom DROP available');
    }
}
