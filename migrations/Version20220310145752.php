<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310145752 extends AbstractMigration
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
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, place_id INT DEFAULT NULL, user_id INT DEFAULT NULL, uuid VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, estimated_at DATE NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, status VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_9CE12A31D17F50A6 (uuid), INDEX IDX_9CE12A31DA6A219 (place_id), UNIQUE INDEX UNIQ_9CE12A31A76ED395 (user_id), INDEX status_idx (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('ALTER TABLE album CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE album_file CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE file ADD extension VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, ADD type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, ADD mime_type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, ADD size INT NOT NULL, CHANGE uuid uuid VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE following CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE login CHANGE application_version application_version VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE ip_address ip_address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE user_agent user_agent VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE failure_reason failure_reason VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_741D53CDD17F50A6 ON place');
        $this->addSql('ALTER TABLE place DROP uuid, CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE zipcode zipcode VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE latitude latitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE longitude longitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE step CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE token CHANGE value value VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE target target VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE travel CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE budget budget VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE firstname firstname VARCHAR(50) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE lastname lastname VARCHAR(70) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(55) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
