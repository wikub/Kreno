<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222164323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_log ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now()');
        $this->addSql('ALTER TABLE commitment_log ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now()');
        $this->addSql('ALTER TABLE timeslot ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now()');
        $this->addSql('ALTER TABLE timeslot ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE ');
        $this->addSql('ALTER TABLE timeslot ALTER created_at SET DEFAULT now()');
        
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now()');
        $this->addSql('ALTER TABLE "user" ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now()');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP updated_at');
        $this->addSql('ALTER TABLE timeslot DROP updated_at');
        $this->addSql('ALTER TABLE timeslot ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE timeslot ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN timeslot.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE commitment_log DROP created_at');
        $this->addSql('ALTER TABLE commitment_log DROP updated_at');
    }
}
