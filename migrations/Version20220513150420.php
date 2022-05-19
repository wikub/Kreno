<?php

declare(strict_types=1);

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513150420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_activity (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, action_type INT NOT NULL, context LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4CF9ED5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE cycle CHANGE start start DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE finish finish DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE timeslot_template CHANGE start start TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', CHANGE finish finish TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\'');
        $this->addSql('ALTER TABLE week CHANGE start_at start_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_activity');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0.0\' NOT NULL');
        $this->addSql('ALTER TABLE cycle CHANGE start start DATE NOT NULL, CHANGE finish finish DATE NOT NULL');
        $this->addSql('ALTER TABLE tag ADD domain VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE timeslot_template CHANGE start start TIME NOT NULL, CHANGE finish finish TIME NOT NULL');
        $this->addSql('ALTER TABLE week CHANGE start_at start_at DATE NOT NULL');
    }
}
