<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321122417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, levels_id INT NOT NULL, teacher_id INT NOT NULL, course_name VARCHAR(100) NOT NULL, INDEX IDX_169E6FB9AF9C3A25 (levels_id), INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_semestre (course_id INT NOT NULL, semestre_id INT NOT NULL, INDEX IDX_D8DCE2EA591CC992 (course_id), INDEX IDX_D8DCE2EA5577AFDB (semestre_id), PRIMARY KEY(course_id, semestre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facial_recognition_log (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, group_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, level_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rfidcard (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semestre (id INT AUTO_INCREMENT NOT NULL, semestre_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, rfid_card_id INT NOT NULL, facial_recognition_id INT NOT NULL, grou_p_id INT NOT NULL, level_id INT NOT NULL, UNIQUE INDEX UNIQ_B723AF337C1EDE59 (rfid_card_id), UNIQUE INDEX UNIQ_B723AF337B14A092 (facial_recognition_id), INDEX IDX_B723AF335E132AF4 (grou_p_id), INDEX IDX_B723AF335FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, student_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, phone_number VARCHAR(10) NOT NULL, profile_image VARCHAR(255) NOT NULL, gender VARCHAR(10) NOT NULL, email VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D64941807E1D (teacher_id), UNIQUE INDEX UNIQ_8D93D649CB944F1A (student_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9AF9C3A25 FOREIGN KEY (levels_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_semestre ADD CONSTRAINT FK_D8DCE2EA591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_semestre ADD CONSTRAINT FK_D8DCE2EA5577AFDB FOREIGN KEY (semestre_id) REFERENCES semestre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337C1EDE59 FOREIGN KEY (rfid_card_id) REFERENCES rfidcard (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337B14A092 FOREIGN KEY (facial_recognition_id) REFERENCES facial_recognition_log (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF335E132AF4 FOREIGN KEY (grou_p_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF335FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9AF9C3A25');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE course_semestre DROP FOREIGN KEY FK_D8DCE2EA591CC992');
        $this->addSql('ALTER TABLE course_semestre DROP FOREIGN KEY FK_D8DCE2EA5577AFDB');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337C1EDE59');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337B14A092');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF335E132AF4');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF335FB14BA7');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941807E1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CB944F1A');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_semestre');
        $this->addSql('DROP TABLE facial_recognition_log');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE rfidcard');
        $this->addSql('DROP TABLE semestre');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE user');
    }
}
