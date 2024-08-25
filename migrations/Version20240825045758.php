<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240825045758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP role');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0F6A6D5F85E0677 ON teacher (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B0F6A6D5F85E0677 ON teacher');
        $this->addSql('ALTER TABLE teacher ADD role VARCHAR(255) NOT NULL, DROP roles');
    }
}
