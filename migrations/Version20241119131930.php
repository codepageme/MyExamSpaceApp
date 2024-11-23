<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119131930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE register_option ADD question_id INT NOT NULL, ADD correct_answers JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD options JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE register_option ADD CONSTRAINT FK_E936785A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E936785A1E27F6BF ON register_option (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE register_option DROP FOREIGN KEY FK_E936785A1E27F6BF');
        $this->addSql('DROP INDEX UNIQ_E936785A1E27F6BF ON register_option');
        $this->addSql('ALTER TABLE register_option DROP question_id, DROP correct_answers, DROP options');
    }
}
