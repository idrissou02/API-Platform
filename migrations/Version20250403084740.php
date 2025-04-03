<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403084740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_835033F8A4D60759 ON genre (libelle)');
        $this->addSql('ALTER TABLE livre CHANGE auteur_id auteur_id INT NOT NULL, CHANGE prix prix DOUBLE PRECISION DEFAULT NULL, CHANGE année annee INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP INDEX UNIQ_835033F8A4D60759 ON genre');
        $this->addSql('ALTER TABLE livre CHANGE auteur_id auteur_id INT DEFAULT NULL, CHANGE prix prix VARCHAR(255) NOT NULL, CHANGE annee année INT NOT NULL');
    }
}
