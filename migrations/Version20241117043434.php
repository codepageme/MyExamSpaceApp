<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117043434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE correct_answer');
        $this->addSql('ALTER TABLE checkbox_option ADD question_id INT NOT NULL, ADD option_a VARCHAR(255) NOT NULL, ADD option_b VARCHAR(255) NOT NULL, ADD option_c VARCHAR(255) NOT NULL, ADD option_d VARCHAR(255) DEFAULT NULL, ADD option_e VARCHAR(255) DEFAULT NULL, ADD correct_answers JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE checkbox_option ADD CONSTRAINT FK_135553FB1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_135553FB1E27F6BF ON checkbox_option (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE correct_answer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE checkbox_option DROP FOREIGN KEY FK_135553FB1E27F6BF');
        $this->addSql('DROP INDEX IDX_135553FB1E27F6BF ON checkbox_option');
        $this->addSql('ALTER TABLE checkbox_option DROP question_id, DROP option_a, DROP option_b, DROP option_c, DROP option_d, DROP option_e, DROP correct_answers');
    }
}
