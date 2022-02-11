<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210163247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE commitment_contract_regular_timeslot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE commitment_contract_regular_timeslot (id INT NOT NULL, commitment_contrat_id INT NOT NULL, timeslot_template_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_317494472E9373CD ON commitment_contract_regular_timeslot (commitment_contrat_id)');
        $this->addSql('CREATE INDEX IDX_31749447D9CD2D3C ON commitment_contract_regular_timeslot (timeslot_template_id)');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT FK_317494472E9373CD FOREIGN KEY (commitment_contrat_id) REFERENCES commitment_contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT FK_31749447D9CD2D3C FOREIGN KEY (timeslot_template_id) REFERENCES timeslot_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE commitment_contract_regular_timeslot_id_seq CASCADE');
        $this->addSql('DROP TABLE commitment_contract_regular_timeslot');
    }
}
