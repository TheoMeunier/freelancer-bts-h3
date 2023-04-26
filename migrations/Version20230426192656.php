<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426192656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prestation_comments (id INT AUTO_INCREMENT NOT NULL, prestation_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D68E2B399E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestation_comments ADD CONSTRAINT FK_D68E2B399E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('ALTER TABLE user ADD prestation_comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649318B48B7 FOREIGN KEY (prestation_comments_id) REFERENCES prestation_comments (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649318B48B7 ON user (prestation_comments_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649318B48B7');
        $this->addSql('ALTER TABLE prestation_comments DROP FOREIGN KEY FK_D68E2B399E45C554');
        $this->addSql('DROP TABLE prestation_comments');
        $this->addSql('DROP INDEX IDX_8D93D649318B48B7 ON user');
        $this->addSql('ALTER TABLE user DROP prestation_comments_id');
    }
}
