<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202073126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE responses (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, question_id INT NOT NULL, exam_id INT NOT NULL, response VARCHAR(255) NOT NULL, iscorrect TINYINT(1) NOT NULL, INDEX IDX_315F9F94CB944F1A (student_id), INDEX IDX_315F9F941E27F6BF (question_id), INDEX IDX_315F9F94578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE results (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, exam_id INT NOT NULL, score NUMERIC(5, 2) NOT NULL, grade VARCHAR(255) NOT NULL, INDEX IDX_9FA3E414CB944F1A (student_id), INDEX IDX_9FA3E414578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_315F9F94CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_315F9F941E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_315F9F94578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E414CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E414578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C641807E1D');
        $this->addSql('DROP INDEX IDX_38BBA6C641807E1D ON exam');
        $this->addSql('ALTER TABLE exam ADD classroom_id INT NOT NULL, ADD examtype_id INT NOT NULL, ADD date DATETIME NOT NULL, ADD total_questions INT NOT NULL, ADD total_marks INT NOT NULL, ADD duration TIME NOT NULL, DROP title, DROP description, DROP created_at, DROP updated_at, DROP published, CHANGE start_time start_time TIME NOT NULL, CHANGE end_time end_time TIME DEFAULT NULL, CHANGE teacher_id subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6F74C3D9A FOREIGN KEY (examtype_id) REFERENCES examtype (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C623EDC87 ON exam (subject_id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C66278D5A8 ON exam (classroom_id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C6F74C3D9A ON exam (examtype_id)');
        $this->addSql('ALTER TABLE student ADD profile_picture VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_315F9F94CB944F1A');
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_315F9F941E27F6BF');
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_315F9F94578D5E91');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E414CB944F1A');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E414578D5E91');
        $this->addSql('DROP TABLE responses');
        $this->addSql('DROP TABLE results');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C623EDC87');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C66278D5A8');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6F74C3D9A');
        $this->addSql('DROP INDEX IDX_38BBA6C623EDC87 ON exam');
        $this->addSql('DROP INDEX IDX_38BBA6C66278D5A8 ON exam');
        $this->addSql('DROP INDEX IDX_38BBA6C6F74C3D9A ON exam');
        $this->addSql('ALTER TABLE exam ADD teacher_id INT NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL, ADD updated_at DATETIME NOT NULL, ADD published TINYINT(1) NOT NULL, DROP subject_id, DROP classroom_id, DROP examtype_id, DROP total_questions, DROP total_marks, DROP duration, CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL, CHANGE date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C641807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C641807E1D ON exam (teacher_id)');
        $this->addSql('ALTER TABLE student DROP profile_picture, DROP email');
    }
}
