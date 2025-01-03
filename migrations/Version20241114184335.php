<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114184335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radio_option ADD question_id INT NOT NULL, ADD option_a VARCHAR(255) NOT NULL, ADD option_b VARCHAR(255) NOT NULL, ADD option_c VARCHAR(255) NOT NULL, ADD option_d VARCHAR(255) DEFAULT NULL, ADD option_e VARCHAR(255) DEFAULT NULL, ADD correct_answer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE radio_option ADD CONSTRAINT FK_3D2089531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D2089531E27F6BF ON radio_option (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radio_option DROP FOREIGN KEY FK_3D2089531E27F6BF');
        $this->addSql('DROP INDEX UNIQ_3D2089531E27F6BF ON radio_option');
        $this->addSql('ALTER TABLE radio_option DROP question_id, DROP option_a, DROP option_b, DROP option_c, DROP option_d, DROP option_e, DROP correct_answer');
    }
}
