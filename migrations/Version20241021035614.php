<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021035614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD sender VARCHAR(255) NOT NULL, ADD file_attachment VARCHAR(255) DEFAULT NULL, DROP sender_type, DROP sender_id, DROP recipient_id, DROP recipient_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD sender_id INT NOT NULL, ADD recipient_id INT NOT NULL, ADD recipient_type VARCHAR(255) NOT NULL, DROP file_attachment, CHANGE sender sender_type VARCHAR(255) NOT NULL');
    }
}
