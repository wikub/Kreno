<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225124440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD start DATE NOT NULL');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot ADD finish DATE NOT NULL');
        $this->addSql('ALTER TABLE commitment_log ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE commitment_log ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE timeslot ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE timeslot ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE timeslot ALTER updated_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN timeslot.created_at IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE timeslot ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE timeslot ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE timeslot ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN timeslot.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP start');
        $this->addSql('ALTER TABLE commitment_contract_regular_timeslot DROP finish');
        $this->addSql('ALTER TABLE commitment_log ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE commitment_log ALTER updated_at SET DEFAULT \'now()\'');
    }
}
