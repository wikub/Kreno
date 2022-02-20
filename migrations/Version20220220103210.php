<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220103210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE commitment_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE commitment_log (id INT NOT NULL, user_id INT NOT NULL, nb_timeslot INT DEFAULT 0 NOT NULL, nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL, comment TEXT DEFAULT NULL, reference JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA5887CAA76ED395 ON commitment_log (user_id)');
        $this->addSql('ALTER TABLE commitment_log ADD CONSTRAINT FK_EA5887CAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE timeslot ALTER name DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE commitment_log_id_seq CASCADE');
        $this->addSql('DROP TABLE commitment_log');
        $this->addSql('ALTER TABLE timeslot ALTER name SET NOT NULL');
    }
}
