<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121144300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" RENAME COLUMN nom TO name');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN prenom TO firstname');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN categorie TO category');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN inscription TO subscription_type');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN telephone TO phonenumber');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN name TO nom');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN firstname TO prenom');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN category TO categorie');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN subscription_type TO inscription');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN phonenumber TO telephone');
    }
}
