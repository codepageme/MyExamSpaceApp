<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121063623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE theory_classroom (theory_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_E1D3596B6441A32F (theory_id), INDEX IDX_E1D3596B6278D5A8 (classroom_id), PRIMARY KEY(theory_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE theory_classroom ADD CONSTRAINT FK_E1D3596B6441A32F FOREIGN KEY (theory_id) REFERENCES theory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theory_classroom ADD CONSTRAINT FK_E1D3596B6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theory ADD subject_id INT NOT NULL, ADD term_id INT NOT NULL, ADD session_id INT NOT NULL, ADD exam_type_id INT NOT NULL, ADD question_type_id INT NOT NULL, ADD teacher_id INT NOT NULL, ADD created_at DATETIME NOT NULL, CHANGE question question LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B3823EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B38E2C35FC FOREIGN KEY (term_id) REFERENCES term (id)');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B38613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B38C8EAD5FB FOREIGN KEY (exam_type_id) REFERENCES examtype (id)');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B38CB90598E FOREIGN KEY (question_type_id) REFERENCES question_type (id)');
        $this->addSql('ALTER TABLE theory ADD CONSTRAINT FK_F3908B3841807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_F3908B3823EDC87 ON theory (subject_id)');
        $this->addSql('CREATE INDEX IDX_F3908B38E2C35FC ON theory (term_id)');
        $this->addSql('CREATE INDEX IDX_F3908B38613FECDF ON theory (session_id)');
        $this->addSql('CREATE INDEX IDX_F3908B38C8EAD5FB ON theory (exam_type_id)');
        $this->addSql('CREATE INDEX IDX_F3908B38CB90598E ON theory (question_type_id)');
        $this->addSql('CREATE INDEX IDX_F3908B3841807E1D ON theory (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE theory_classroom DROP FOREIGN KEY FK_E1D3596B6441A32F');
        $this->addSql('ALTER TABLE theory_classroom DROP FOREIGN KEY FK_E1D3596B6278D5A8');
        $this->addSql('DROP TABLE theory_classroom');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B3823EDC87');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B38E2C35FC');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B38613FECDF');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B38C8EAD5FB');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B38CB90598E');
        $this->addSql('ALTER TABLE theory DROP FOREIGN KEY FK_F3908B3841807E1D');
        $this->addSql('DROP INDEX IDX_F3908B3823EDC87 ON theory');
        $this->addSql('DROP INDEX IDX_F3908B38E2C35FC ON theory');
        $this->addSql('DROP INDEX IDX_F3908B38613FECDF ON theory');
        $this->addSql('DROP INDEX IDX_F3908B38C8EAD5FB ON theory');
        $this->addSql('DROP INDEX IDX_F3908B38CB90598E ON theory');
        $this->addSql('DROP INDEX IDX_F3908B3841807E1D ON theory');
        $this->addSql('ALTER TABLE theory DROP subject_id, DROP term_id, DROP session_id, DROP exam_type_id, DROP question_type_id, DROP teacher_id, DROP created_at, CHANGE question question VARCHAR(750) NOT NULL');
    }
}
