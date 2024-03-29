<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329000630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337C1EDE59');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337B14A092');
        $this->addSql('ALTER TABLE student CHANGE rfid_card_id rfid_card_id INT DEFAULT NULL, CHANGE facial_recognition_id facial_recognition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337C1EDE59 FOREIGN KEY (rfid_card_id) REFERENCES rfidcard (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337B14A092 FOREIGN KEY (facial_recognition_id) REFERENCES facial_recognition_log (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337C1EDE59');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337B14A092');
        $this->addSql('ALTER TABLE student CHANGE rfid_card_id rfid_card_id INT NOT NULL, CHANGE facial_recognition_id facial_recognition_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337C1EDE59 FOREIGN KEY (rfid_card_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337B14A092 FOREIGN KEY (facial_recognition_id) REFERENCES student (id)');
    }
}
