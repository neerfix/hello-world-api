<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218083614 extends AbstractMigration
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
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, travel_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_39986E43ECAB15B3 (travel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE album_step (album_id INT NOT NULL, step_id INT NOT NULL, INDEX IDX_27B2487C1137ABCF (album_id), INDEX IDX_27B2487C73B21E9C (step_id), PRIMARY KEY(album_id, step_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE album_file (file_id INT NOT NULL, album_id INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, sequence INT NOT NULL, INDEX IDX_E894805093CB796C (file_id), INDEX IDX_E89480501137ABCF (album_id), PRIMARY KEY(file_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, extension VARCHAR(255) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, user_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE following (main_user_id INT NOT NULL, follower_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_71BF8DE353257A7C (main_user_id), INDEX IDX_71BF8DE3AC24F853 (follower_id), PRIMARY KEY(main_user_id, follower_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, application_version VARCHAR(255) NOT NULL, ip_address VARCHAR(255) NOT NULL, user_agent VARCHAR(255) NOT NULL, is_successful TINYINT(1) NOT NULL, failure_reason VARCHAR(255) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_AA08CB10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, travel_id VARCHAR(255) DEFAULT NULL, place_id VARCHAR(255) NOT NULL, started_at DATE NOT NULL, ended_at DATE NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE step_albums (step_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_D9FC8A3573B21E9C (step_id), INDEX IDX_D9FC8A351137ABCF (album_id), PRIMARY KEY(step_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE travel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, budget VARCHAR(255) NOT NULL, is_shared TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, started_at DATE NOT NULL, ended_at DATE NOT NULL, user_id INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(70) DEFAULT NULL, username VARCHAR(55) NOT NULL, is_verify VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id);');
        $this->addSql('ALTER TABLE album_step ADD CONSTRAINT FK_27B2487C1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE album_step ADD CONSTRAINT FK_27B2487C73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE album_file ADD CONSTRAINT FK_E894805093CB796C FOREIGN KEY (file_id) REFERENCES file (id);');
        $this->addSql('ALTER TABLE album_file ADD CONSTRAINT FK_E89480501137ABCF FOREIGN KEY (album_id) REFERENCES album (id);');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE353257A7C FOREIGN KEY (main_user_id) REFERENCES user (id);');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id);');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);');
        $this->addSql('ALTER TABLE step_albums ADD CONSTRAINT FK_D9FC8A3573B21E9C FOREIGN KEY (step_id) REFERENCES step (id);');
        $this->addSql('ALTER TABLE step_albums ADD CONSTRAINT FK_D9FC8A351137ABCF FOREIGN KEY (album_id) REFERENCES album (id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_step DROP FOREIGN KEY FK_27B2487C1137ABCF');
        $this->addSql('ALTER TABLE album_file DROP FOREIGN KEY FK_E89480501137ABCF');
        $this->addSql('ALTER TABLE step_albums DROP FOREIGN KEY FK_D9FC8A351137ABCF');
        $this->addSql('ALTER TABLE album_file DROP FOREIGN KEY FK_E894805093CB796C');
        $this->addSql('ALTER TABLE album_step DROP FOREIGN KEY FK_27B2487C73B21E9C');
        $this->addSql('ALTER TABLE step_albums DROP FOREIGN KEY FK_D9FC8A3573B21E9C');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43ECAB15B3');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE353257A7C');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3AC24F853');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB10A76ED395');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_step');
        $this->addSql('DROP TABLE album_file');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE step_albums');
        $this->addSql('DROP TABLE travel');
        $this->addSql('DROP TABLE user');
    }
}
