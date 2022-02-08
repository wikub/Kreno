<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204151151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP CONSTRAINT fk_fbd8e0f8bb5d5477');
        $this->addSql('DROP INDEX idx_fbd8e0f8bb5d5477');
        $this->addSql('ALTER TABLE job DROP user_category_id');
        $this->addSql('ALTER TABLE timeslot_template ADD nb_job INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE job ADD user_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT fk_fbd8e0f8bb5d5477 FOREIGN KEY (user_category_id) REFERENCES user_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fbd8e0f8bb5d5477 ON job (user_category_id)');
        $this->addSql('ALTER TABLE timeslot_template DROP nb_job');
    }
}
