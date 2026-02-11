<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211121745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_58562B47B03A8386 (created_by_id), INDEX IDX_58562B47896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_list (id INT AUTO_INCREMENT NOT NULL, board_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_archived TINYINT(1) DEFAULT 0 NOT NULL, color VARCHAR(32) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9E5EA13BE7EC5785 (board_id), INDEX IDX_9E5EA13BB03A8386 (created_by_id), INDEX IDX_9E5EA13B896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL, is_archived TINYINT(1) DEFAULT 0 NOT NULL, deadline DATETIME DEFAULT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_161498D33DAE168B (list_id), INDEX IDX_161498D3B03A8386 (created_by_id), INDEX IDX_161498D3896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_executor (card_id INT NOT NULL, executor_id INT NOT NULL, INDEX IDX_4E4345574ACC9A20 (card_id), INDEX IDX_4E4345578ABD09BB (executor_id), PRIMARY KEY(card_id, executor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_log (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, created_by_id INT DEFAULT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DFCFEC044ACC9A20 (card_id), INDEX IDX_DFCFEC04B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B47B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B47896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE board_list ADD CONSTRAINT FK_9E5EA13BE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_list ADD CONSTRAINT FK_9E5EA13BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE board_list ADD CONSTRAINT FK_9E5EA13B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D33DAE168B FOREIGN KEY (list_id) REFERENCES board_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE card_executor ADD CONSTRAINT FK_4E4345574ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_executor ADD CONSTRAINT FK_4E4345578ABD09BB FOREIGN KEY (executor_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_log ADD CONSTRAINT FK_DFCFEC044ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_log ADD CONSTRAINT FK_DFCFEC04B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B47B03A8386');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B47896DBBDE');
        $this->addSql('ALTER TABLE board_list DROP FOREIGN KEY FK_9E5EA13BE7EC5785');
        $this->addSql('ALTER TABLE board_list DROP FOREIGN KEY FK_9E5EA13BB03A8386');
        $this->addSql('ALTER TABLE board_list DROP FOREIGN KEY FK_9E5EA13B896DBBDE');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D33DAE168B');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3B03A8386');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3896DBBDE');
        $this->addSql('ALTER TABLE card_executor DROP FOREIGN KEY FK_4E4345574ACC9A20');
        $this->addSql('ALTER TABLE card_executor DROP FOREIGN KEY FK_4E4345578ABD09BB');
        $this->addSql('ALTER TABLE card_log DROP FOREIGN KEY FK_DFCFEC044ACC9A20');
        $this->addSql('ALTER TABLE card_log DROP FOREIGN KEY FK_DFCFEC04B03A8386');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE board_list');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_executor');
        $this->addSql('DROP TABLE card_log');
    }
}
