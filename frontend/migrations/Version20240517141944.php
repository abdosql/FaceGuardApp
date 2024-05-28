<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517141944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_year_semester (academic_year_id INT NOT NULL, semester_id INT NOT NULL, INDEX IDX_586BF262C54F3401 (academic_year_id), INDEX IDX_586BF2624A798B6F (semester_id), PRIMARY KEY(academic_year_id, semester_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE academic_year_semester ADD CONSTRAINT FK_586BF262C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE academic_year_semester ADD CONSTRAINT FK_586BF2624A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academic_year_semester DROP FOREIGN KEY FK_586BF262C54F3401');
        $this->addSql('ALTER TABLE academic_year_semester DROP FOREIGN KEY FK_586BF2624A798B6F');
        $this->addSql('DROP TABLE academic_year_semester');
    }
}
