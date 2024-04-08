<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403020024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_year (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE branch (id INT AUTO_INCREMENT NOT NULL, branch_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE branch_teacher (branch_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_AB0262DBDCD6CC49 (branch_id), INDEX IDX_AB0262DB41807E1D (teacher_id), PRIMARY KEY(branch_id, teacher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE branch_course (branch_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_E3FF1AECDCD6CC49 (branch_id), INDEX IDX_E3FF1AEC591CC992 (course_id), PRIMARY KEY(branch_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, course_name VARCHAR(100) NOT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facial_recognition_log (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, group_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rfidcard (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, semester_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, group__id INT DEFAULT NULL, rfid_card_id INT DEFAULT NULL, facial_recognition_id INT DEFAULT NULL, branch_id INT DEFAULT NULL, academic_year_id INT DEFAULT NULL, INDEX IDX_B723AF33E5D32D49 (group__id), UNIQUE INDEX UNIQ_B723AF337C1EDE59 (rfid_card_id), UNIQUE INDEX UNIQ_B723AF337B14A092 (facial_recognition_id), INDEX IDX_B723AF33DCD6CC49 (branch_id), INDEX IDX_B723AF33C54F3401 (academic_year_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher_student (teacher_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_7AE1227241807E1D (teacher_id), INDEX IDX_7AE12272CB944F1A (student_id), PRIMARY KEY(teacher_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, phone_number VARCHAR(10) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, gender VARCHAR(10) NOT NULL, email VARCHAR(100) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE branch_teacher ADD CONSTRAINT FK_AB0262DBDCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE branch_teacher ADD CONSTRAINT FK_AB0262DB41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE branch_course ADD CONSTRAINT FK_E3FF1AECDCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE branch_course ADD CONSTRAINT FK_E3FF1AEC591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33E5D32D49 FOREIGN KEY (group__id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337C1EDE59 FOREIGN KEY (rfid_card_id) REFERENCES rfidcard (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337B14A092 FOREIGN KEY (facial_recognition_id) REFERENCES facial_recognition_log (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_student ADD CONSTRAINT FK_7AE1227241807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_student ADD CONSTRAINT FK_7AE12272CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE branch_teacher DROP FOREIGN KEY FK_AB0262DBDCD6CC49');
        $this->addSql('ALTER TABLE branch_teacher DROP FOREIGN KEY FK_AB0262DB41807E1D');
        $this->addSql('ALTER TABLE branch_course DROP FOREIGN KEY FK_E3FF1AECDCD6CC49');
        $this->addSql('ALTER TABLE branch_course DROP FOREIGN KEY FK_E3FF1AEC591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33E5D32D49');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337C1EDE59');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337B14A092');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33DCD6CC49');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33C54F3401');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5BF396750');
        $this->addSql('ALTER TABLE teacher_student DROP FOREIGN KEY FK_7AE1227241807E1D');
        $this->addSql('ALTER TABLE teacher_student DROP FOREIGN KEY FK_7AE12272CB944F1A');
        $this->addSql('DROP TABLE academic_year');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE branch_teacher');
        $this->addSql('DROP TABLE branch_course');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE facial_recognition_log');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE rfidcard');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE teacher_student');
        $this->addSql('DROP TABLE user');
    }
}
