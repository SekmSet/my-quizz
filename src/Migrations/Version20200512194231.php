<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200512194231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, categorie_id INT NOT NULL, INDEX IDX_EDBFD5ECA76ED395 (user_id), INDEX IDX_EDBFD5ECBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_reponse (id INT AUTO_INCREMENT NOT NULL, historique_id INT NOT NULL, reponse_id INT NOT NULL, INDEX IDX_7BBC0CD6128735E (historique_id), INDEX IDX_7BBC0CDCF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user_reponse ADD CONSTRAINT FK_7BBC0CD6128735E FOREIGN KEY (historique_id) REFERENCES historique (id)');
        $this->addSql('ALTER TABLE user_reponse ADD CONSTRAINT FK_7BBC0CDCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_reponse DROP FOREIGN KEY FK_7BBC0CD6128735E');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE user_reponse');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
