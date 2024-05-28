<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520121747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP INDEX UNIQ_D044D5D4591CC992, ADD INDEX IDX_D044D5D4591CC992 (course_id)');
        $this->addSql('ALTER TABLE session CHANGE course_id course_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP INDEX IDX_D044D5D4591CC992, ADD UNIQUE INDEX UNIQ_D044D5D4591CC992 (course_id)');
        $this->addSql('ALTER TABLE session CHANGE course_id course_id INT NOT NULL');
    }
}
