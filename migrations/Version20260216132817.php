<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216132817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card_pattern (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, deadline DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_F4A676F8B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_pattern ADD CONSTRAINT FK_F4A676F8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE RESTRICT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_pattern DROP FOREIGN KEY FK_F4A676F8B03A8386');
        $this->addSql('DROP TABLE card_pattern');
    }
}
