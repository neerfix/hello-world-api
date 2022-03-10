<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310132049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE album_step');
        $this->addSql('DROP TABLE step_albums');
        $this->addSql('ALTER TABLE step ADD album_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43B9FE3C1137ABCF ON step (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_step (album_id INT NOT NULL, step_id INT NOT NULL, INDEX IDX_27B2487C1137ABCF (album_id), INDEX IDX_27B2487C73B21E9C (step_id), PRIMARY KEY(album_id, step_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE step_albums (step_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_D9FC8A351137ABCF (album_id), INDEX IDX_D9FC8A3573B21E9C (step_id), PRIMARY KEY(step_id, album_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE album_step ADD CONSTRAINT FK_27B2487C1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_step ADD CONSTRAINT FK_27B2487C73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step_albums ADD CONSTRAINT FK_D9FC8A351137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE step_albums ADD CONSTRAINT FK_D9FC8A3573B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE album CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE album_file CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE file CHANGE extension extension VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE uuid uuid VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE mime_type mime_type VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE following CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE login CHANGE application_version application_version VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE ip_address ip_address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE user_agent user_agent VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE failure_reason failure_reason VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE place CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE zipcode zipcode VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE latitude latitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE longitude longitude VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C1137ABCF');
        $this->addSql('DROP INDEX UNIQ_43B9FE3C1137ABCF ON step');
        $this->addSql('ALTER TABLE step DROP album_id, CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE token CHANGE value value VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE target target VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE travel CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE budget budget VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE firstname firstname VARCHAR(50) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE lastname lastname VARCHAR(70) DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(55) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
