<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502165639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messageries (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, prestataire_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_12AC6BD619EB6921 (client_id), INDEX IDX_12AC6BD6BE3DB2B7 (prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, prestataire_id INT DEFAULT NULL, messageries_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DB021E9619EB6921 (client_id), INDEX IDX_DB021E96BE3DB2B7 (prestataire_id), INDEX IDX_DB021E96B62CD10F (messageries_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messageries ADD CONSTRAINT FK_12AC6BD619EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messageries ADD CONSTRAINT FK_12AC6BD6BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9619EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96B62CD10F FOREIGN KEY (messageries_id) REFERENCES messageries (id)');
        $this->addSql('ALTER TABLE category_prestation DROP FOREIGN KEY FK_77D6DFD312469DE2');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messageries DROP FOREIGN KEY FK_12AC6BD619EB6921');
        $this->addSql('ALTER TABLE messageries DROP FOREIGN KEY FK_12AC6BD6BE3DB2B7');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9619EB6921');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96BE3DB2B7');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96B62CD10F');
        $this->addSql('DROP TABLE messageries');
        $this->addSql('DROP TABLE messages');
        $this->addSql('ALTER TABLE category_prestation DROP FOREIGN KEY FK_77D6DFD312469DE2');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }
}
