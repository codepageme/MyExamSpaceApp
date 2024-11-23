<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121052359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_option ADD question_id INT NOT NULL, ADD image_path VARCHAR(255) NOT NULL, ADD correct_answer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE images_option ADD CONSTRAINT FK_987CC7F61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_987CC7F61E27F6BF ON images_option (question_id)');
        $this->addSql('ALTER TABLE theory ADD question VARCHAR(750) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_option DROP FOREIGN KEY FK_987CC7F61E27F6BF');
        $this->addSql('DROP INDEX UNIQ_987CC7F61E27F6BF ON images_option');
        $this->addSql('ALTER TABLE images_option DROP question_id, DROP image_path, DROP correct_answer');
        $this->addSql('ALTER TABLE theory DROP question');
    }
}
