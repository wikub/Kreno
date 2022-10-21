<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017133457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_log (id INT AUTO_INCREMENT NOT NULL, email_to VARCHAR(255) NOT NULL, name_to VARCHAR(255) DEFAULT NULL, subject VARCHAR(255) NOT NULL, html_content LONGTEXT DEFAULT NULL, text_content LONGTEXT DEFAULT NULL, sended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE email_log');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0.0\' NOT NULL');
    }
}
