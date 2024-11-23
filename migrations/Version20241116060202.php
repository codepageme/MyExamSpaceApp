<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241116060202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question_classroom (question_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_40A874061E27F6BF (question_id), INDEX IDX_40A874066278D5A8 (classroom_id), PRIMARY KEY(question_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_classroom ADD CONSTRAINT FK_40A874061E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_classroom ADD CONSTRAINT FK_40A874066278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E6278D5A8');
        $this->addSql('DROP INDEX IDX_B6F7494E6278D5A8 ON question');
        $this->addSql('ALTER TABLE question DROP classroom_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_classroom DROP FOREIGN KEY FK_40A874061E27F6BF');
        $this->addSql('ALTER TABLE question_classroom DROP FOREIGN KEY FK_40A874066278D5A8');
        $this->addSql('DROP TABLE question_classroom');
        $this->addSql('ALTER TABLE question ADD classroom_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E6278D5A8 ON question (classroom_id)');
    }
}
