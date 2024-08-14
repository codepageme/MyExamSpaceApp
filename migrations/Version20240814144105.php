<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814144105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prefix VARCHAR(180) NOT NULL COLLATE "BINARY", first_name VARCHAR(255) NOT NULL COLLATE "BINARY", last_name VARCHAR(255) NOT NULL COLLATE "BINARY", username VARCHAR(255) NOT NULL COLLATE "BINARY", password VARCHAR(255) NOT NULL COLLATE "BINARY", contact VARCHAR(255) DEFAULT NULL COLLATE "BINARY", email VARCHAR(255) DEFAULT NULL COLLATE "BINARY", role VARCHAR(255) DEFAULT NULL COLLATE "BINARY")');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL COLLATE "BINARY", password VARCHAR(255) NOT NULL COLLATE "BINARY", email VARCHAR(255) DEFAULT NULL COLLATE "BINARY", gender VARCHAR(255) NOT NULL COLLATE "BINARY", return VARCHAR(255) NOT NULL COLLATE "BINARY")');
        $this->addSql('DROP TABLE teache');
    }
}
