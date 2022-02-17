<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217105056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, travel_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_39986E439D86650F (user_id_id), INDEX IDX_39986E43D7E819E1 (travel_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_file (id INT AUTO_INCREMENT NOT NULL, file_id_id INT NOT NULL, album_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', sequence INT NOT NULL, INDEX IDX_E8948050D5C72E60 (file_id_id), INDEX IDX_E89480509FCD471 (album_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, extension VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, size INT NOT NULL, INDEX IDX_8C9F36109D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, id_main_user_id INT NOT NULL, id_follower_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_71BF8DE370E40F0E (id_main_user_id), INDEX IDX_71BF8DE3B43635A4 (id_follower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, application_version VARCHAR(255) DEFAULT NULL, ip_address VARCHAR(50) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, is_successful TINYINT(1) DEFAULT NULL, failureÃ_reason VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AA08CB109D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, travel_id_id INT NOT NULL, placeÃ_id_id INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_43B9FE3CD7E819E1 (travel_id_id), INDEX IDX_43B9FE3C7FDA69EA (placeÃ_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step_album (step_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_25BA99BC73B21E9C (step_id), INDEX IDX_25BA99BC1137ABCF (album_id), PRIMARY KEY(step_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travel (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, budget DOUBLE PRECISION DEFAULT NULL, is_shared TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', createdÃ_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2D0B6BCE9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_picture_id INT DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', profile_Ãpicture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649292E8AE2 (profile_picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E439D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43D7E819E1 FOREIGN KEY (travel_id_id) REFERENCES travel (id)');
        $this->addSql('ALTER TABLE album_file ADD CONSTRAINT FK_E8948050D5C72E60 FOREIGN KEY (file_id_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE album_file ADD CONSTRAINT FK_E89480509FCD471 FOREIGN KEY (album_id_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36109D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE370E40F0E FOREIGN KEY (id_main_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3B43635A4 FOREIGN KEY (id_follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB109D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3CD7E819E1 FOREIGN KEY (travel_id_id) REFERENCES travel (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C7FDA69EA FOREIGN KEY (placeÃ_id_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE step_album ADD CONSTRAINT FK_25BA99BC73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step_album ADD CONSTRAINT FK_25BA99BC1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649292E8AE2 FOREIGN KEY (profile_picture_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_file DROP FOREIGN KEY FK_E89480509FCD471');
        $this->addSql('ALTER TABLE step_album DROP FOREIGN KEY FK_25BA99BC1137ABCF');
        $this->addSql('ALTER TABLE album_file DROP FOREIGN KEY FK_E8948050D5C72E60');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649292E8AE2');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C7FDA69EA');
        $this->addSql('ALTER TABLE step_album DROP FOREIGN KEY FK_25BA99BC73B21E9C');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43D7E819E1');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3CD7E819E1');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E439D86650F');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36109D86650F');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE370E40F0E');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3B43635A4');
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB109D86650F');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCE9D86650F');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_file');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE step_album');
        $this->addSql('DROP TABLE travel');
        $this->addSql('DROP TABLE user');
    }
}
