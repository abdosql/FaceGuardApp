<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504231800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course CHANGE course_duration course_duration INT NOT NULL');
        $this->addSql('ALTER TABLE time_schedule ADD semester_id INT NOT NULL');
        $this->addSql('ALTER TABLE time_schedule ADD CONSTRAINT FK_FEBD52374A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_FEBD52374A798B6F ON time_schedule (semester_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course CHANGE course_duration course_duration TIME NOT NULL');
        $this->addSql('ALTER TABLE time_schedule DROP FOREIGN KEY FK_FEBD52374A798B6F');
        $this->addSql('DROP INDEX IDX_FEBD52374A798B6F ON time_schedule');
        $this->addSql('ALTER TABLE time_schedule DROP semester_id');
    }
}
