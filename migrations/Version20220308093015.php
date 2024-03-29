<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308093015 extends AbstractMigration
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
        $this->addSql('ALTER TABLE album ADD uuid VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39986E43D17F50A6 ON album (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_39986E43D17F50A6 ON album');
        $this->addSql('ALTER TABLE album DROP uuid, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE album_file CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE file CHANGE extension extension VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE user_id user_id INT DEFAULT NULL, CHANGE uuid uuid VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE mime_type mime_type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE following CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE login CHANGE application_version application_version VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE ip_address ip_address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE user_agent user_agent VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE failure_reason failure_reason VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE place CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE zipcode zipcode VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE latitude latitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE longitude longitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE step CHANGE travel_id travel_id VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE place_id place_id VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE token CHANGE value value VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE target target VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEA76ED395');
        $this->addSql('DROP INDEX IDX_2D0B6BCEA76ED395 ON travel');
        $this->addSql('ALTER TABLE travel CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE budget budget VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE firstname firstname VARCHAR(50) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE lastname lastname VARCHAR(70) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(55) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
