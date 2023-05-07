<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507120319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CF624B39D');
        $this->addSql('DROP INDEX IDX_14E8F60CF624B39D ON messagerie');
        $this->addSql('ALTER TABLE messagerie CHANGE sender_id seeder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CF423E767 FOREIGN KEY (seeder_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14E8F60CF423E767 ON messagerie (seeder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CF423E767');
        $this->addSql('DROP INDEX IDX_14E8F60CF423E767 ON messagerie');
        $this->addSql('ALTER TABLE messagerie CHANGE seeder_id sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14E8F60CF624B39D ON messagerie (sender_id)');
    }
}
