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
final class Version20220603180938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE cycle CHANGE start start DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE finish finish DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE timeslot_template CHANGE start start TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', CHANGE finish finish TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\'');
        $this->addSql('ALTER TABLE week CHANGE start_at start_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE commitment_log CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0.0\' NOT NULL');
        $this->addSql('ALTER TABLE cycle CHANGE start start DATE NOT NULL, CHANGE finish finish DATE NOT NULL');
        $this->addSql('ALTER TABLE timeslot_template CHANGE start start TIME NOT NULL, CHANGE finish finish TIME NOT NULL');
        $this->addSql('ALTER TABLE week CHANGE start_at start_at DATE NOT NULL');
    }
}
