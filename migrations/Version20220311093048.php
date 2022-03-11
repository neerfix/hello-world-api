<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311093048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place RENAME INDEX uniq_39956e49d17f70a6 TO UNIQ_741D53CDD17F50A6;');
        $this->addSql('ALTER TABLE travel ADD place_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEDA6A219 FOREIGN KEY (place_id) REFERENCES place (id);');
        $this->addSql(' CREATE INDEX IDX_2D0B6BCEDA6A219 ON travel (place_id);');
        $this->addSql('ALTER TABLE step ADD uuid VARCHAR(180) NOT NULL;');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43B9FE3CD17F50A6 ON step (uuid);');
        $this->addSql('DROP INDEX UNIQ_2D4B9BCED17F51A8 ON file;');
        $this->addSql('ALTER TABLE place CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE zipcode zipcode VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place RENAME INDEX UNIQ_741D53CDD17F50A6 TO uniq_39956e49d17f70a6;');
        $this->addSql('ALTER TABLE travel DROP place_id;');
        $this->addSql('ALTER TABLE travel DROP CONSTRAINT FK_2D0B6BCEDA6A219;');
        $this->addSql('DROP INDEX IDX_2D0B6BCEDA6A219 ON travel (place_id);');
        $this->addSql('ALTER TABLE step DROP uuid;');
        $this->addSql('DROP INDEX UNIQ_43B9FE3CD17F50A6 ON step (uuid);');
        $this->addSql('CREATE INDEX UNIQ_2D4B9BCED17F51A8 ON file(uuid);');
        $this->addSql('ALTER TABLE place CHANGE address address VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(255) NOT NULL, CHANGE zipcode zipcode VARCHAR(255) NOT NULL, CHANGE country country VARCHAR(255) NOT NULL;');
    }
}
