<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014061350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coffee_details RENAME INDEX uniq_bec4977d4584665a TO UNIQ_35FC778C4584665A');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a14959723 TO IDX_B3BA5A5A78FF0845');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coffee_details RENAME INDEX uniq_35fc778c4584665a TO UNIQ_BEC4977D4584665A');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a78ff0845 TO IDX_B3BA5A5A14959723');
    }
}
