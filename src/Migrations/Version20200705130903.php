<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200705130903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE nomade_annonce (nomade_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_B11E450BABE0C7CB (nomade_id), INDEX IDX_B11E450B8805AB2F (annonce_id), PRIMARY KEY(nomade_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nomade_annonce ADD CONSTRAINT FK_B11E450BABE0C7CB FOREIGN KEY (nomade_id) REFERENCES nomade (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nomade_annonce ADD CONSTRAINT FK_B11E450B8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5ABE0C7CB');
        $this->addSql('DROP INDEX IDX_F65593E5ABE0C7CB ON annonce');
        $this->addSql('ALTER TABLE annonce DROP nomade_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE nomade_annonce');
        $this->addSql('ALTER TABLE annonce ADD nomade_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5ABE0C7CB FOREIGN KEY (nomade_id) REFERENCES nomade (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5ABE0C7CB ON annonce (nomade_id)');
    }
}
