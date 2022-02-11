<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211143718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP CONSTRAINT fk_317494472e9373cd');
        $this->addSql('DROP INDEX idx_317494472e9373cd');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot RENAME COLUMN commitment_contrat_id TO commitment_contract_id');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT FK_317494474D9DE370 FOREIGN KEY (commitment_contract_id) REFERENCES commitment_contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_317494474D9DE370 ON commitment_contract_regular_timeslot (commitment_contract_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP CONSTRAINT FK_317494474D9DE370');
        $this->addSql('DROP INDEX IDX_317494474D9DE370');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot RENAME COLUMN commitment_contract_id TO commitment_contrat_id');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD CONSTRAINT fk_317494472e9373cd FOREIGN KEY (commitment_contrat_id) REFERENCES commitment_contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_317494472e9373cd ON commitment_contract_regular_timeslot (commitment_contrat_id)');
    }
}
