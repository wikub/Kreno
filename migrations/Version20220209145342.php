<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209145342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE week_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE week (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, week_type INT NOT NULL, start_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE timeslot ADD week_id INT NOT NULL');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F7C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BE452F7C86F3B2F ON timeslot (week_id)');
        $this->addSql('ALTER TABLE week_template ALTER week_type DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE timeslot DROP CONSTRAINT FK_3BE452F7C86F3B2F');
        $this->addSql('DROP SEQUENCE week_id_seq CASCADE');
        $this->addSql('DROP TABLE week');
        $this->addSql('ALTER TABLE week_template ALTER week_type SET DEFAULT 1');
        $this->addSql('DROP INDEX IDX_3BE452F7C86F3B2F');
        $this->addSql('ALTER TABLE timeslot DROP week_id');
    }
}
