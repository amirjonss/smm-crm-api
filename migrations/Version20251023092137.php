<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251023092137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create content plan table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE content_plan (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, post VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, date DATE NOT NULL, idea LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1038B6B8166D1F9C (project_id), INDEX IDX_1038B6B8B03A8386 (created_by_id), INDEX IDX_1038B6B8896DBBDE (updated_by_id), INDEX IDX_1038B6B8C76F1F52 (deleted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan ADD CONSTRAINT FK_1038B6B8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan ADD CONSTRAINT FK_1038B6B8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan ADD CONSTRAINT FK_1038B6B8896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan ADD CONSTRAINT FK_1038B6B8C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan DROP FOREIGN KEY FK_1038B6B8166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan DROP FOREIGN KEY FK_1038B6B8B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan DROP FOREIGN KEY FK_1038B6B8896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE content_plan DROP FOREIGN KEY FK_1038B6B8C76F1F52
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE content_plan
        SQL);
    }
}
