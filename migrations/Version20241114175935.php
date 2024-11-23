<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114175935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD subject_id INT NOT NULL, DROP subject');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E23EDC87 ON question (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E23EDC87');
        $this->addSql('DROP INDEX IDX_B6F7494E23EDC87 ON question');
        $this->addSql('ALTER TABLE question ADD subject VARCHAR(255) NOT NULL, DROP subject_id');
    }
}
