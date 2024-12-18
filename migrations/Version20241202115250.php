<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202115250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE results ADD academic_calender_id INT NOT NULL');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E414332A932D FOREIGN KEY (academic_calender_id) REFERENCES academic_calender (id)');
        $this->addSql('CREATE INDEX IDX_9FA3E414332A932D ON results (academic_calender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E414332A932D');
        $this->addSql('DROP INDEX IDX_9FA3E414332A932D ON results');
        $this->addSql('ALTER TABLE results DROP academic_calender_id');
    }
}
