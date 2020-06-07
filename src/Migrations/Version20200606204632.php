<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606204632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, proprio_id INT NOT NULL, titre VARCHAR(255) NOT NULL, type_logement VARCHAR(255) NOT NULL, nombre_max_residents INT NOT NULL, description LONGTEXT DEFAULT NULL, superficie DOUBLE PRECISION NOT NULL, tarif INT NOT NULL, date_disponible DATE NOT NULL, adresse VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, publication_auth TINYINT(1) NOT NULL, INDEX IDX_F65593E56B82600 (proprio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_nomade (annonce_id INT NOT NULL, nomade_id INT NOT NULL, INDEX IDX_44E999B68805AB2F (annonce_id), INDEX IDX_44E999B6ABE0C7CB (nomade_id), PRIMARY KEY(annonce_id, nomade_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nomade (id INT AUTO_INCREMENT NOT NULL, nom_photo VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, cp INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, budget DOUBLE PRECISION DEFAULT NULL, presentation LONGTEXT DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, date_creation_compte DATETIME NOT NULL, sexe VARCHAR(255) DEFAULT NULL, is_confirmed TINYINT(1) NOT NULL, security_token VARCHAR(255) NOT NULL, type_sejour VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_244E9AA8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', raison_social VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, is_confirmed TINYINT(1) NOT NULL, security_token VARCHAR(255) NOT NULL, date_creation_compte DATETIME NOT NULL, refus INT NOT NULL, refus_token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E56B82600 FOREIGN KEY (proprio_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE annonce_nomade ADD CONSTRAINT FK_44E999B68805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_nomade ADD CONSTRAINT FK_44E999B6ABE0C7CB FOREIGN KEY (nomade_id) REFERENCES nomade (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonce_nomade DROP FOREIGN KEY FK_44E999B68805AB2F');
        $this->addSql('ALTER TABLE annonce_nomade DROP FOREIGN KEY FK_44E999B6ABE0C7CB');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E56B82600');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_nomade');
        $this->addSql('DROP TABLE nomade');
        $this->addSql('DROP TABLE proprietaire');
    }
}
