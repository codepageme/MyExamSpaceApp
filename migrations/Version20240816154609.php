<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816154609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__teacher AS SELECT id, prefix, first_name, last_name, username, password, number, email, role FROM teacher');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('CREATE TABLE teacher (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prefix VARCHAR(120) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, number INTEGER DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO teacher (id, prefix, first_name, last_name, username, password, number, email, role) SELECT id, prefix, first_name, last_name, username, password, number, email, role FROM __temp__teacher');
        $this->addSql('DROP TABLE __temp__teacher');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher ADD COLUMN string VARCHAR(255) NOT NULL');
    }
}
