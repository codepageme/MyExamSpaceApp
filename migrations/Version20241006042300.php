<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241006042300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher_subject_class ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher_subject_class MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON teacher_subject_class');
        $this->addSql('ALTER TABLE teacher_subject_class DROP id');
        $this->addSql('ALTER TABLE teacher_subject_class ADD PRIMARY KEY (teacher_subject_id, classroom_id)');
    }
}
