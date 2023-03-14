<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314135450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE information_user ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE information_user ADD CONSTRAINT FK_AC3BDF54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC3BDF54A76ED395 ON information_user (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BA5F7304');
        $this->addSql('DROP INDEX IDX_8D93D649BA5F7304 ON user');
        $this->addSql('ALTER TABLE user DROP information_user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD information_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BA5F7304 FOREIGN KEY (information_user_id) REFERENCES information_user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BA5F7304 ON user (information_user_id)');
        $this->addSql('ALTER TABLE information_user DROP FOREIGN KEY FK_AC3BDF54A76ED395');
        $this->addSql('DROP INDEX UNIQ_AC3BDF54A76ED395 ON information_user');
        $this->addSql('ALTER TABLE information_user DROP user_id');
    }
}
