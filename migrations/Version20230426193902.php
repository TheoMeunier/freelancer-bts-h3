<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426193902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation_comments ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation_comments ADD CONSTRAINT FK_D68E2B39A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D68E2B39A76ED395 ON prestation_comments (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649318B48B7');
        $this->addSql('DROP INDEX IDX_8D93D649318B48B7 ON user');
        $this->addSql('ALTER TABLE user DROP prestation_comments_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD prestation_comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649318B48B7 FOREIGN KEY (prestation_comments_id) REFERENCES prestation_comments (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649318B48B7 ON user (prestation_comments_id)');
        $this->addSql('ALTER TABLE prestation_comments DROP FOREIGN KEY FK_D68E2B39A76ED395');
        $this->addSql('DROP INDEX IDX_D68E2B39A76ED395 ON prestation_comments');
        $this->addSql('ALTER TABLE prestation_comments DROP user_id');
    }
}
