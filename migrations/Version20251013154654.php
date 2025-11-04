<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251013154654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_details (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, isbn VARCHAR(255) NOT NULL, pages INT NOT NULL, UNIQUE INDEX UNIQ_E106231C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, quantity_in_stock INT NOT NULL, last_restock_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_B12D4A364584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_details ADD CONSTRAINT FK_E106231C4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A364584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE coffee_inventory ADD roast_level VARCHAR(255) NOT NULL, ADD weight_per_package NUMERIC(10, 2) NOT NULL, ADD flavor_description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE coffee_inventory RENAME INDEX uniq_bec4977dde18e50b TO UNIQ_BEC4977D4584665A');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_details DROP FOREIGN KEY FK_E106231C4584665A');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A364584665A');
        $this->addSql('DROP TABLE book_details');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('ALTER TABLE coffee_inventory DROP roast_level, DROP weight_per_package, DROP flavor_description');
        $this->addSql('ALTER TABLE coffee_inventory RENAME INDEX uniq_bec4977d4584665a TO UNIQ_BEC4977DDE18E50B');
    }
}
