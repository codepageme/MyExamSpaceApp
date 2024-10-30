<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241006033640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher_subject_class (teacher_subject_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_3499563388217E27 (teacher_subject_id), INDEX IDX_349956336278D5A8 (classroom_id), PRIMARY KEY(teacher_subject_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE teacher_subject_class ADD CONSTRAINT FK_3499563388217E27 FOREIGN KEY (teacher_subject_id) REFERENCES teacher_subject (id)');
        $this->addSql('ALTER TABLE teacher_subject_class ADD CONSTRAINT FK_349956336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE teacher_subject_classroom DROP FOREIGN KEY FK_790791306278D5A8');
        $this->addSql('ALTER TABLE teacher_subject_classroom DROP FOREIGN KEY FK_7907913023EDC87');
        $this->addSql('ALTER TABLE teacher_subject_classroom DROP FOREIGN KEY FK_79079130AE80F5DF');
        $this->addSql('ALTER TABLE teacher_subject_classroom DROP FOREIGN KEY FK_7907913041807E1D');
        $this->addSql('DROP TABLE teacher_subject_classroom');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher_subject_classroom (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, subject_id INT NOT NULL, classroom_id INT NOT NULL, department_id INT DEFAULT NULL, INDEX IDX_7907913023EDC87 (subject_id), INDEX IDX_790791306278D5A8 (classroom_id), INDEX IDX_79079130AE80F5DF (department_id), INDEX IDX_7907913041807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET armscii8 COLLATE `armscii8_bin` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE teacher_subject_classroom ADD CONSTRAINT FK_790791306278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE teacher_subject_classroom ADD CONSTRAINT FK_7907913023EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE teacher_subject_classroom ADD CONSTRAINT FK_79079130AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE teacher_subject_classroom ADD CONSTRAINT FK_7907913041807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE teacher_subject_class DROP FOREIGN KEY FK_3499563388217E27');
        $this->addSql('ALTER TABLE teacher_subject_class DROP FOREIGN KEY FK_349956336278D5A8');
        $this->addSql('DROP TABLE teacher_subject_class');
    }
}
