<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418184837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_prestation (category_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_77D6DFD312469DE2 (category_id), INDEX IDX_77D6DFD39E45C554 (prestation_id), PRIMARY KEY(category_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD39E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_prestation DROP FOREIGN KEY FK_77D6DFD312469DE2');
        $this->addSql('ALTER TABLE category_prestation DROP FOREIGN KEY FK_77D6DFD39E45C554');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_prestation');
    }
}
