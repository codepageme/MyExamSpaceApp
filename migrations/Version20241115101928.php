<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115101928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE academic_calender_session DROP FOREIGN KEY FK_5154BA34332A932D');
        $this->addSql('ALTER TABLE academic_calender_session DROP FOREIGN KEY FK_5154BA34613FECDF');
        $this->addSql('ALTER TABLE academic_calender_term DROP FOREIGN KEY FK_854E5A20E2C35FC');
        $this->addSql('ALTER TABLE academic_calender_term DROP FOREIGN KEY FK_854E5A20332A932D');
        $this->addSql('DROP TABLE academic_calender');
        $this->addSql('DROP TABLE academic_calender_session');
        $this->addSql('DROP TABLE academic_calender_term');
        $this->addSql('DROP TABLE session');
        $this->addSql('ALTER TABLE term DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_calender (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE academic_calender_session (academic_calender_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_5154BA34332A932D (academic_calender_id), INDEX IDX_5154BA34613FECDF (session_id), PRIMARY KEY(academic_calender_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE academic_calender_term (academic_calender_id INT NOT NULL, term_id INT NOT NULL, INDEX IDX_854E5A20332A932D (academic_calender_id), INDEX IDX_854E5A20E2C35FC (term_id), PRIMARY KEY(academic_calender_id, term_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE academic_calender_session ADD CONSTRAINT FK_5154BA34332A932D FOREIGN KEY (academic_calender_id) REFERENCES academic_calender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE academic_calender_session ADD CONSTRAINT FK_5154BA34613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE academic_calender_term ADD CONSTRAINT FK_854E5A20E2C35FC FOREIGN KEY (term_id) REFERENCES term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE academic_calender_term ADD CONSTRAINT FK_854E5A20332A932D FOREIGN KEY (academic_calender_id) REFERENCES academic_calender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE term ADD name VARCHAR(50) NOT NULL');
    }
}