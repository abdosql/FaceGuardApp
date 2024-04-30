<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430001025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, student_id INT NOT NULL, attending TINYINT(1) NOT NULL, INDEX IDX_6DE30D91613FECDF (session_id), UNIQUE INDEX UNIQ_6DE30D91CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, classroom_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_semester (course_id INT NOT NULL, semester_id INT NOT NULL, INDEX IDX_5E8CE3BB591CC992 (course_id), INDEX IDX_5E8CE3BB4A798B6F (semester_id), PRIMARY KEY(course_id, semester_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, classroom_id INT NOT NULL, start_hour DATETIME NOT NULL, end_hour DATETIME NOT NULL, day INT NOT NULL, UNIQUE INDEX UNIQ_D044D5D4591CC992 (course_id), UNIQUE INDEX UNIQ_D044D5D46278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_schedule (id INT AUTO_INCREMENT NOT NULL, group__id INT NOT NULL, UNIQUE INDEX UNIQ_FEBD5237E5D32D49 (group__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_schedule_session (time_schedule_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_CE1E78C47A38C63C (time_schedule_id), INDEX IDX_CE1E78C4613FECDF (session_id), PRIMARY KEY(time_schedule_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE course_semester ADD CONSTRAINT FK_5E8CE3BB591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_semester ADD CONSTRAINT FK_5E8CE3BB4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D46278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE time_schedule ADD CONSTRAINT FK_FEBD5237E5D32D49 FOREIGN KEY (group__id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE time_schedule_session ADD CONSTRAINT FK_CE1E78C47A38C63C FOREIGN KEY (time_schedule_id) REFERENCES time_schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE time_schedule_session ADD CONSTRAINT FK_CE1E78C4613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91613FECDF');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91CB944F1A');
        $this->addSql('ALTER TABLE course_semester DROP FOREIGN KEY FK_5E8CE3BB591CC992');
        $this->addSql('ALTER TABLE course_semester DROP FOREIGN KEY FK_5E8CE3BB4A798B6F');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4591CC992');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D46278D5A8');
        $this->addSql('ALTER TABLE time_schedule DROP FOREIGN KEY FK_FEBD5237E5D32D49');
        $this->addSql('ALTER TABLE time_schedule_session DROP FOREIGN KEY FK_CE1E78C47A38C63C');
        $this->addSql('ALTER TABLE time_schedule_session DROP FOREIGN KEY FK_CE1E78C4613FECDF');
        $this->addSql('DROP TABLE attendance');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE course_semester');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE time_schedule');
        $this->addSql('DROP TABLE time_schedule_session');
    }
}
