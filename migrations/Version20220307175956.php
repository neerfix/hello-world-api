<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307175956 extends AbstractMigration
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
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);');
        $this->addSql('CREATE INDEX IDX_8C9F3610A76ED395 ON file (user_id);');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);');
        $this->addSql('CREATE INDEX IDX_2D0B6BCEA76ED395 ON travel (user_id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
