<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930051024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F8C4FFF86');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1FD3B6AF');
        $this->addSql('DROP INDEX IDX_B6BD307F1FD3B6AF ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F8C4FFF86 ON message');
        $this->addSql('ALTER TABLE message ADD sender_id INT NOT NULL, DROP admin_sender_id, DROP teacher_sender_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('ALTER TABLE message ADD admin_sender_id INT DEFAULT NULL, ADD teacher_sender_id INT DEFAULT NULL, DROP sender_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F8C4FFF86 FOREIGN KEY (teacher_sender_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1FD3B6AF FOREIGN KEY (admin_sender_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F1FD3B6AF ON message (admin_sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F8C4FFF86 ON message (teacher_sender_id)');
    }
}
