<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520131533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_schedule DROP INDEX UNIQ_FEBD52374A798B6F, ADD INDEX IDX_FEBD52374A798B6F (semester_id)');
        $this->addSql('ALTER TABLE time_schedule CHANGE semester_id semester_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_schedule DROP INDEX IDX_FEBD52374A798B6F, ADD UNIQUE INDEX UNIQ_FEBD52374A798B6F (semester_id)');
        $this->addSql('ALTER TABLE time_schedule CHANGE semester_id semester_id INT NOT NULL');
    }
}
