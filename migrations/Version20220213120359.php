<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213120359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD manager BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE timeslot DROP CONSTRAINT fk_3be452f7783e3463');
        $this->addSql('DROP INDEX idx_3be452f7783e3463');
        $this->addSql('ALTER TABLE timeslot DROP manager_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job DROP manager');
        $this->addSql('ALTER TABLE timeslot ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT fk_3be452f7783e3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3be452f7783e3463 ON timeslot (manager_id)');
    }
}
