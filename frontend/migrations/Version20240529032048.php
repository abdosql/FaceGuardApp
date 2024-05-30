<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529032048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher_group (teacher_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_3C3FF89841807E1D (teacher_id), INDEX IDX_3C3FF898FE54D947 (group_id), PRIMARY KEY(teacher_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE teacher_group ADD CONSTRAINT FK_3C3FF89841807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_group ADD CONSTRAINT FK_3C3FF898FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher_group DROP FOREIGN KEY FK_3C3FF89841807E1D');
        $this->addSql('ALTER TABLE teacher_group DROP FOREIGN KEY FK_3C3FF898FE54D947');
        $this->addSql('DROP TABLE teacher_group');
    }
}
