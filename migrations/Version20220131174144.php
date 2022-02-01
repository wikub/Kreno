<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131174144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE commitment_contract_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE commitment_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE commitment_contract (id INT NOT NULL, type_id INT NOT NULL, user_id INT NOT NULL, start DATE NOT NULL, finish DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B0D1ACA1C54C8C93 ON commitment_contract (type_id)');
        $this->addSql('CREATE INDEX IDX_B0D1ACA1A76ED395 ON commitment_contract (user_id)');
        $this->addSql('CREATE TABLE commitment_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE commitment_contract ADD CONSTRAINT FK_B0D1ACA1C54C8C93 FOREIGN KEY (type_id) REFERENCES commitment_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commitment_contract ADD CONSTRAINT FK_B0D1ACA1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE commitment_contract DROP CONSTRAINT FK_B0D1ACA1C54C8C93');
        $this->addSql('DROP SEQUENCE commitment_contract_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE commitment_type_id_seq CASCADE');
        $this->addSql('DROP TABLE commitment_contract');
        $this->addSql('DROP TABLE commitment_type');
    }
}
