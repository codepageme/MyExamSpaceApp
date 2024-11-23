<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114175138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE term (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD examtype_id INT NOT NULL, ADD classroom_id INT NOT NULL, ADD term_id INT NOT NULL, ADD session VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF74C3D9A FOREIGN KEY (examtype_id) REFERENCES examtype (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EE2C35FC FOREIGN KEY (term_id) REFERENCES term (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EF74C3D9A ON question (examtype_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E6278D5A8 ON question (classroom_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EE2C35FC ON question (term_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EE2C35FC');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE term');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EF74C3D9A');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E6278D5A8');
        $this->addSql('DROP INDEX IDX_B6F7494EF74C3D9A ON question');
        $this->addSql('DROP INDEX IDX_B6F7494E6278D5A8 ON question');
        $this->addSql('DROP INDEX IDX_B6F7494EE2C35FC ON question');
        $this->addSql('ALTER TABLE question DROP examtype_id, DROP classroom_id, DROP term_id, DROP session, DROP created_at');
    }
}
