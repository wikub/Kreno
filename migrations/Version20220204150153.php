<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204150153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE timeslot_template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE week_template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE timeslot_template (id INT NOT NULL, timeslot_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, day_week INT NOT NULL, start TIME(0) WITHOUT TIME ZONE NOT NULL, finish TIME(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5667561ABC45820 ON timeslot_template (timeslot_type_id)');
        $this->addSql('CREATE TABLE week_template (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE timeslot_template ADD CONSTRAINT FK_C5667561ABC45820 FOREIGN KEY (timeslot_type_id) REFERENCES timeslot_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE timeslot ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timeslot ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F7783E3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BE452F7783E3463 ON timeslot (manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE timeslot_template_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE week_template_id_seq CASCADE');
        $this->addSql('DROP TABLE timeslot_template');
        $this->addSql('DROP TABLE week_template');
        $this->addSql('ALTER TABLE timeslot DROP CONSTRAINT FK_3BE452F7783E3463');
        $this->addSql('DROP INDEX IDX_3BE452F7783E3463');
        $this->addSql('ALTER TABLE timeslot DROP manager_id');
        $this->addSql('ALTER TABLE timeslot ALTER status SET DEFAULT \'\'');
    }
}
