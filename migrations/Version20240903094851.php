<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903094851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, questiontype_id INT NOT NULL, teacher_id INT NOT NULL, content LONGTEXT NOT NULL, class VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E3B224418 (questiontype_id), INDEX IDX_B6F7494E41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3B224418 FOREIGN KEY (questiontype_id) REFERENCES question_type (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E3B224418');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E41807E1D');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_type');
    }
}
