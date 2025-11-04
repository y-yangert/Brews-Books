<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015111112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E106231CCC1CF4E6 ON book_details (isbn)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A79B17AE9 ON products (sku_code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC28B95C5E237E06 ON suppliers (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_AC28B95C5E237E06 ON suppliers');
        $this->addSql('DROP INDEX UNIQ_E106231CCC1CF4E6 ON book_details');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A79B17AE9 ON products');
    }
}
