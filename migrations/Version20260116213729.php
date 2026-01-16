<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116213729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content_plan_content_plan_platform (content_plan_id INT NOT NULL, content_plan_platform_id INT NOT NULL, INDEX IDX_8124C2873791DB28 (content_plan_id), INDEX IDX_8124C287B363E0F6 (content_plan_platform_id), PRIMARY KEY(content_plan_id, content_plan_platform_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_plan_platform (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT \'NOT_PUBLISHED\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_plan_content_plan_platform ADD CONSTRAINT FK_8124C2873791DB28 FOREIGN KEY (content_plan_id) REFERENCES content_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_plan_content_plan_platform ADD CONSTRAINT FK_8124C287B363E0F6 FOREIGN KEY (content_plan_platform_id) REFERENCES content_plan_platform (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_plan_content_plan_platform DROP FOREIGN KEY FK_8124C2873791DB28');
        $this->addSql('ALTER TABLE content_plan_content_plan_platform DROP FOREIGN KEY FK_8124C287B363E0F6');
        $this->addSql('DROP TABLE content_plan_content_plan_platform');
        $this->addSql('DROP TABLE content_plan_platform');
    }
}
