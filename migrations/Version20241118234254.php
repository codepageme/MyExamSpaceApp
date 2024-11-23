<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118234254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boolean_option ADD question_id INT NOT NULL, ADD correct_answer TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE boolean_option ADD CONSTRAINT FK_12ED1AB91E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_12ED1AB91E27F6BF ON boolean_option (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boolean_option DROP FOREIGN KEY FK_12ED1AB91E27F6BF');
        $this->addSql('DROP INDEX UNIQ_12ED1AB91E27F6BF ON boolean_option');
        $this->addSql('ALTER TABLE boolean_option DROP question_id, DROP correct_answer');
    }
}
