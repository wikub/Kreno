<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220226171241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commitment_contract (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, user_id INT NOT NULL, start DATE NOT NULL, finish DATE DEFAULT NULL, INDEX IDX_B0D1ACA1C54C8C93 (type_id), INDEX IDX_B0D1ACA1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commitment_contract_regular_timeslot (id INT AUTO_INCREMENT NOT NULL, commitment_contract_id INT NOT NULL, timeslot_template_id INT NOT NULL, start DATE NOT NULL, finish DATE DEFAULT NULL, INDEX IDX_317494474D9DE370 (commitment_contract_id), INDEX IDX_31749447D9CD2D3C (timeslot_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commitment_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nb_timeslot INT DEFAULT 0 NOT NULL, nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL, comment LONGTEXT DEFAULT NULL, reference LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EA5887CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commitment_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, nb_timeslot_min INT DEFAULT NULL, nb_hour_min NUMERIC(10, 1) DEFAULT NULL, regular TINYINT(1) DEFAULT 0 NOT NULL, manager TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, timeslot_id INT NOT NULL, job_done_id INT DEFAULT NULL, manager TINYINT(1) NOT NULL, INDEX IDX_FBD8E0F8A76ED395 (user_id), INDEX IDX_FBD8E0F8F920B9E9 (timeslot_id), INDEX IDX_FBD8E0F832226FA3 (job_done_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_done_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, commitment_calculation TINYINT(1) NOT NULL, position INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeslot (id INT AUTO_INCREMENT NOT NULL, timeslot_type_id INT DEFAULT NULL, user_validation_id INT DEFAULT NULL, week_id INT NOT NULL, template_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, start DATETIME NOT NULL, finish DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, comment_validation LONGTEXT DEFAULT NULL, validation_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3BE452F7ABC45820 (timeslot_type_id), INDEX IDX_3BE452F74CA9E500 (user_validation_id), INDEX IDX_3BE452F7C86F3B2F (week_id), INDEX IDX_3BE452F75DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeslot_template (id INT AUTO_INCREMENT NOT NULL, timeslot_type_id INT NOT NULL, week_template_id INT NOT NULL, name VARCHAR(255) NOT NULL, day_week INT NOT NULL, start TIME NOT NULL, finish TIME NOT NULL, description LONGTEXT DEFAULT NULL, nb_job INT NOT NULL, INDEX IDX_C5667561ABC45820 (timeslot_type_id), INDEX IDX_C56675617E6B04C1 (week_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeslot_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user_category_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, phonenumber VARCHAR(50) DEFAULT NULL, subscription_type INT NOT NULL, email VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649BB5D5477 (user_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, week_type INT NOT NULL, start_at DATE NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX start_idx (start_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, week_type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commitment_contract ADD CONSTRAINT FK_B0D1ACA1C54C8C93 FOREIGN KEY (type_id) REFERENCES commitment_type (id)');
        $this->addSql('ALTER TABLE commitment_contract ADD CONSTRAINT FK_B0D1ACA1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT FK_317494474D9DE370 FOREIGN KEY (commitment_contract_id) REFERENCES commitment_contract (id)');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT FK_31749447D9CD2D3C FOREIGN KEY (timeslot_template_id) REFERENCES timeslot_template (id)');
        $this->addSql('ALTER TABLE commitment_log ADD CONSTRAINT FK_EA5887CAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8F920B9E9 FOREIGN KEY (timeslot_id) REFERENCES timeslot (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F832226FA3 FOREIGN KEY (job_done_id) REFERENCES job_done_type (id)');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F7ABC45820 FOREIGN KEY (timeslot_type_id) REFERENCES timeslot_type (id)');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F74CA9E500 FOREIGN KEY (user_validation_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F7C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F75DA0FB8 FOREIGN KEY (template_id) REFERENCES timeslot_template (id)');
        $this->addSql('ALTER TABLE timeslot_template ADD CONSTRAINT FK_C5667561ABC45820 FOREIGN KEY (timeslot_type_id) REFERENCES timeslot_type (id)');
        $this->addSql('ALTER TABLE timeslot_template ADD CONSTRAINT FK_C56675617E6B04C1 FOREIGN KEY (week_template_id) REFERENCES week_template (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BB5D5477 FOREIGN KEY (user_category_id) REFERENCES user_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP FOREIGN KEY FK_317494474D9DE370');
        $this->addSql('ALTER TABLE commitment_contract DROP FOREIGN KEY FK_B0D1ACA1C54C8C93');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F832226FA3');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8F920B9E9');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP FOREIGN KEY FK_31749447D9CD2D3C');
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F75DA0FB8');
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F7ABC45820');
        $this->addSql('ALTER TABLE timeslot_template DROP FOREIGN KEY FK_C5667561ABC45820');
        $this->addSql('ALTER TABLE commitment_contract DROP FOREIGN KEY FK_B0D1ACA1A76ED395');
        $this->addSql('ALTER TABLE commitment_log DROP FOREIGN KEY FK_EA5887CAA76ED395');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A76ED395');
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F74CA9E500');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BB5D5477');
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F7C86F3B2F');
        $this->addSql('ALTER TABLE timeslot_template DROP FOREIGN KEY FK_C56675617E6B04C1');
        $this->addSql('DROP TABLE commitment_contract');
        $this->addSql('DROP TABLE commitment_contract_regular_timeslot');
        $this->addSql('DROP TABLE commitment_log');
        $this->addSql('DROP TABLE commitment_type');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE job_done_type');
        $this->addSql('DROP TABLE timeslot');
        $this->addSql('DROP TABLE timeslot_template');
        $this->addSql('DROP TABLE timeslot_type');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('DROP TABLE week');
        $this->addSql('DROP TABLE week_template');
    }
}
