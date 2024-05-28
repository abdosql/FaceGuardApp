<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520104436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_schedule_session DROP FOREIGN KEY FK_CE1E78C4613FECDF');
        $this->addSql('ALTER TABLE time_schedule_session DROP FOREIGN KEY FK_CE1E78C47A38C63C');
        $this->addSql('DROP TABLE time_schedule_session');
        $this->addSql('ALTER TABLE session ADD time_schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D47A38C63C FOREIGN KEY (time_schedule_id) REFERENCES time_schedule (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D47A38C63C ON session (time_schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_schedule_session (time_schedule_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_CE1E78C47A38C63C (time_schedule_id), INDEX IDX_CE1E78C4613FECDF (session_id), PRIMARY KEY(time_schedule_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE time_schedule_session ADD CONSTRAINT FK_CE1E78C4613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE time_schedule_session ADD CONSTRAINT FK_CE1E78C47A38C63C FOREIGN KEY (time_schedule_id) REFERENCES time_schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D47A38C63C');
        $this->addSql('DROP INDEX IDX_D044D5D47A38C63C ON session');
        $this->addSql('ALTER TABLE session DROP time_schedule_id');
    }
}
