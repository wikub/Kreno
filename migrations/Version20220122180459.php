<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122180459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE timeslot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE timeslot_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE job (id INT NOT NULL, user_category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8BB5D5477 ON job (user_category_id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8A76ED395 ON job (user_id)');
        $this->addSql('CREATE TABLE timeslot (id INT NOT NULL, timeslot_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finish TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BE452F7ABC45820 ON timeslot (timeslot_type_id)');
        $this->addSql('CREATE TABLE timeslot_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8BB5D5477 FOREIGN KEY (user_category_id) REFERENCES user_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F7ABC45820 FOREIGN KEY (timeslot_type_id) REFERENCES timeslot_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD user_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD enabled BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP category');
        $this->addSql('ALTER TABLE "user" DROP enable');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649BB5D5477 FOREIGN KEY (user_category_id) REFERENCES user_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649BB5D5477 ON "user" (user_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE timeslot DROP CONSTRAINT FK_3BE452F7ABC45820');
        $this->addSql('ALTER TABLE job DROP CONSTRAINT FK_FBD8E0F8BB5D5477');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649BB5D5477');
        $this->addSql('DROP SEQUENCE job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE timeslot_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE timeslot_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_category_id_seq CASCADE');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE timeslot');
        $this->addSql('DROP TABLE timeslot_type');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('DROP INDEX IDX_8D93D649BB5D5477');
        $this->addSql('ALTER TABLE "user" ADD category INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD enable INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP user_category_id');
        $this->addSql('ALTER TABLE "user" DROP enabled');
    }
}
