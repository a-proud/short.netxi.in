<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716230300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entity_relation (id INT AUTO_INCREMENT NOT NULL, owner_entity VARCHAR(100) NOT NULL, owner_entity_id BIGINT NOT NULL, related_entity VARCHAR(100) NOT NULL, related_entity_id BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE short_url (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) NOT NULL, url VARCHAR(2000) NOT NULL, ttl INT NOT NULL, created DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entity_relation');
        $this->addSql('DROP TABLE short_url');
        $this->addSql('DROP TABLE tag');
    }
}
