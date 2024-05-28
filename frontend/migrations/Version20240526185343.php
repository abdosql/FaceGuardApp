<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526185343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_academic_year (course_id INT NOT NULL, academic_year_id INT NOT NULL, INDEX IDX_E4D7A3F8591CC992 (course_id), INDEX IDX_E4D7A3F8C54F3401 (academic_year_id), PRIMARY KEY(course_id, academic_year_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_academic_year ADD CONSTRAINT FK_E4D7A3F8591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_academic_year ADD CONSTRAINT FK_E4D7A3F8C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_academic_year DROP FOREIGN KEY FK_E4D7A3F8591CC992');
        $this->addSql('ALTER TABLE course_academic_year DROP FOREIGN KEY FK_E4D7A3F8C54F3401');
        $this->addSql('DROP TABLE course_academic_year');
    }
}
