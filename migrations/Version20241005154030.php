<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241005154030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subject_teacher (subject_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_15740A7723EDC87 (subject_id), INDEX IDX_15740A7741807E1D (teacher_id), PRIMARY KEY(subject_id, teacher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher_subject (teacher_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_360CB33B41807E1D (teacher_id), INDEX IDX_360CB33B23EDC87 (subject_id), PRIMARY KEY(teacher_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_teacher ADD CONSTRAINT FK_15740A7723EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_teacher ADD CONSTRAINT FK_15740A7741807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_subject ADD CONSTRAINT FK_360CB33B41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_subject ADD CONSTRAINT FK_360CB33B23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_teacher DROP FOREIGN KEY FK_15740A7723EDC87');
        $this->addSql('ALTER TABLE subject_teacher DROP FOREIGN KEY FK_15740A7741807E1D');
        $this->addSql('ALTER TABLE teacher_subject DROP FOREIGN KEY FK_360CB33B41807E1D');
        $this->addSql('ALTER TABLE teacher_subject DROP FOREIGN KEY FK_360CB33B23EDC87');
        $this->addSql('DROP TABLE subject_teacher');
        $this->addSql('DROP TABLE teacher_subject');
    }
}
