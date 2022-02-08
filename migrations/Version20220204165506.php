<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204165506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeslot_template ADD week_template_id INT NOT NULL');
        $this->addSql('ALTER TABLE timeslot_template ADD CONSTRAINT FK_C56675617E6B04C1 FOREIGN KEY (week_template_id) REFERENCES week_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C56675617E6B04C1 ON timeslot_template (week_template_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE timeslot_template DROP CONSTRAINT FK_C56675617E6B04C1');
        $this->addSql('DROP INDEX IDX_C56675617E6B04C1');
        $this->addSql('ALTER TABLE timeslot_template DROP week_template_id');
    }
}
