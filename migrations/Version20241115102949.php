<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115102949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_calender (id INT AUTO_INCREMENT NOT NULL, term_id INT NOT NULL, session_id INT NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_35F5EC55E2C35FC (term_id), INDEX IDX_35F5EC55613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE academic_calender ADD CONSTRAINT FK_35F5EC55E2C35FC FOREIGN KEY (term_id) REFERENCES term (id)');
        $this->addSql('ALTER TABLE academic_calender ADD CONSTRAINT FK_35F5EC55613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE term ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academic_calender DROP FOREIGN KEY FK_35F5EC55E2C35FC');
        $this->addSql('ALTER TABLE academic_calender DROP FOREIGN KEY FK_35F5EC55613FECDF');
        $this->addSql('DROP TABLE academic_calender');
        $this->addSql('DROP TABLE session');
        $this->addSql('ALTER TABLE term DROP name');
    }
}
