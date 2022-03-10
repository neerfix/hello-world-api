<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310155818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D4B9BCED17F51A8 ON file (uuid);');
        $this->addSql('ALTER TABLE file DROP extension;');
        $this->addSql('ALTER TABLE file DROP type;');
        $this->addSql('ALTER TABLE file DROP mime_type;');
        $this->addSql('ALTER TABLE file DROP size;');
        $this->addSql('ALTER TABLE file ADD path VARCHAR(255) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2D4B9BCED17F51A8 ON file;');
        $this->addSql('ALTER TABLE file ADD extension VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE file ADD type VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE file ADD mime_type VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE file ADD size VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE file DROP path;');
    }
}
