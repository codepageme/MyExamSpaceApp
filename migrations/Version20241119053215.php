<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119053215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE german_option ADD question_id INT DEFAULT NULL, ADD correct_answer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE german_option ADD CONSTRAINT FK_FEF2FA4D1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF2FA4D1E27F6BF ON german_option (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE german_option DROP FOREIGN KEY FK_FEF2FA4D1E27F6BF');
        $this->addSql('DROP INDEX UNIQ_FEF2FA4D1E27F6BF ON german_option');
        $this->addSql('ALTER TABLE german_option DROP question_id, DROP correct_answer');
    }
}
