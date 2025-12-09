<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251013075356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coffee_inventory ADD product_id_id INT NOT NULL, ADD roast_date DATE NOT NULL, ADD expiration_date DATE NOT NULL, ADD quantity_on_hand INT NOT NULL');
        $this->addSql('ALTER TABLE coffee_inventory ADD CONSTRAINT FK_BEC4977DDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEC4977DDE18E50B ON coffee_inventory (product_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coffee_inventory DROP FOREIGN KEY FK_BEC4977DDE18E50B');
        $this->addSql('DROP INDEX UNIQ_BEC4977DDE18E50B ON coffee_inventory');
        $this->addSql('ALTER TABLE coffee_inventory DROP product_id_id, DROP roast_date, DROP expiration_date, DROP quantity_on_hand');
    }
}
