<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814144717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__teacher AS SELECT id, username, password FROM teacher');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('CREATE TABLE teacher (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO teacher (id, username, password) SELECT id, username, password FROM __temp__teacher');
        $this->addSql('DROP TABLE __temp__teacher');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL COLLATE "BINARY", password VARCHAR(255) NOT NULL COLLATE "BINARY", email VARCHAR(255) DEFAULT NULL COLLATE "BINARY", gender VARCHAR(255) NOT NULL COLLATE "BINARY", return VARCHAR(255) NOT NULL COLLATE "BINARY")');
        $this->addSql('ALTER TABLE teacher ADD COLUMN prefix VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE teacher ADD COLUMN first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teacher ADD COLUMN last_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teacher ADD COLUMN contact VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher ADD COLUMN email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher ADD COLUMN role VARCHAR(255) DEFAULT NULL');
    }
}
