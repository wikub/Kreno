<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201170426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD job_done_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F832226FA3 FOREIGN KEY (job_done_id) REFERENCES job_done_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FBD8E0F832226FA3 ON job (job_done_id)');
        $this->addSql('ALTER TABLE timeslot ADD user_validation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timeslot ADD comment_validation TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE timeslot ADD validation_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN timeslot.validation_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F74CA9E500 FOREIGN KEY (user_validation_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BE452F74CA9E500 ON timeslot (user_validation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job DROP CONSTRAINT FK_FBD8E0F832226FA3');
        $this->addSql('DROP INDEX IDX_FBD8E0F832226FA3');
        $this->addSql('ALTER TABLE job DROP job_done_id');
        $this->addSql('ALTER TABLE timeslot DROP CONSTRAINT FK_3BE452F74CA9E500');
        $this->addSql('DROP INDEX IDX_3BE452F74CA9E500');
        $this->addSql('ALTER TABLE timeslot DROP user_validation_id');
        $this->addSql('ALTER TABLE timeslot DROP comment_validation');
        $this->addSql('ALTER TABLE timeslot DROP validation_at');
    }
}
