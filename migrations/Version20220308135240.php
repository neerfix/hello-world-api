<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308135240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel ADD uuid VARCHAR(180) NOT NULL;');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D0B6BCED17F50A6 ON travel (uuid);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
