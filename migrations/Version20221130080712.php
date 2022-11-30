<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130080712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, id_category_id INT NOT NULL, module_name VARCHAR(50) NOT NULL, INDEX IDX_C242628A545015 (id_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_module (session_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_634F2C71613FECDF (session_id), INDEX IDX_634F2C71AFC2B591 (module_id), PRIMARY KEY(session_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, sex VARCHAR(10) NOT NULL, phone_number INT NOT NULL, register_date DATETIME NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_session (user_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_8849CBDEA76ED395 (user_id), INDEX IDX_8849CBDE613FECDF (session_id), PRIMARY KEY(user_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628A545015 FOREIGN KEY (id_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE session_module ADD CONSTRAINT FK_634F2C71613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_module ADD CONSTRAINT FK_634F2C71AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_session ADD CONSTRAINT FK_8849CBDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_session ADD CONSTRAINT FK_8849CBDE613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628A545015');
        $this->addSql('ALTER TABLE session_module DROP FOREIGN KEY FK_634F2C71613FECDF');
        $this->addSql('ALTER TABLE session_module DROP FOREIGN KEY FK_634F2C71AFC2B591');
        $this->addSql('ALTER TABLE user_session DROP FOREIGN KEY FK_8849CBDEA76ED395');
        $this->addSql('ALTER TABLE user_session DROP FOREIGN KEY FK_8849CBDE613FECDF');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_module');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_session');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
