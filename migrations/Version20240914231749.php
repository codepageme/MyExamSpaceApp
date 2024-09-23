<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240914231749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD email VARCHAR(255) DEFAULT NULL, ADD role VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76F85E0677 ON admin (username)');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX fk_7907913041807e1d TO IDX_7907913041807E1D');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX fk_7907913023edc87 TO IDX_7907913023EDC87');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX fk_790791306278d5a8 TO IDX_790791306278D5A8');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_880E0D76F85E0677 ON admin');
        $this->addSql('ALTER TABLE admin DROP email, DROP role');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX idx_7907913041807e1d TO FK_7907913041807E1D');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX idx_7907913023edc87 TO FK_7907913023EDC87');
        $this->addSql('ALTER TABLE teacher_subject_classroom RENAME INDEX idx_790791306278d5a8 TO FK_790791306278D5A8');
    }
}
