<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014120759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE stocks RENAME INDEX uniq_b12d4a364584665a TO UNIQ_56F798054584665A');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stocks RENAME INDEX uniq_56f798054584665a TO UNIQ_B12D4A364584665A');
        $this->addSql('ALTER TABLE products DROP is_active');
    }
}
