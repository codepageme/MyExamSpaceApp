<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928105503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD teacher_sender_id INT DEFAULT NULL, CHANGE admin_sender_id admin_sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F8C4FFF86 FOREIGN KEY (teacher_sender_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F8C4FFF86 ON message (teacher_sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F8C4FFF86');
        $this->addSql('DROP INDEX IDX_B6BD307F8C4FFF86 ON message');
        $this->addSql('ALTER TABLE message DROP teacher_sender_id, CHANGE admin_sender_id admin_sender_id INT NOT NULL');
    }
}
