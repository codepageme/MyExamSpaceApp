<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925025718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, admin_sender_id INT NOT NULL, content LONGTEXT NOT NULL, sent_at DATETIME NOT NULL, recipient VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F1FD3B6AF (admin_sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, message VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6000B0D3642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_list (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, task VARCHAR(255) DEFAULT NULL, is_completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1B199E07642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1FD3B6AF FOREIGN KEY (admin_sender_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE todo_list ADD CONSTRAINT FK_1B199E07642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE admin ADD profile_picture VARCHAR(255) DEFAULT NULL, ADD last_login DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1FD3B6AF');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3642B8210');
        $this->addSql('ALTER TABLE todo_list DROP FOREIGN KEY FK_1B199E07642B8210');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE todo_list');
        $this->addSql('ALTER TABLE admin DROP profile_picture, DROP last_login');
    }
}
