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
final class Version20221204135922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Optin for Email Notification';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD email_notif_cycle_start TINYINT(1) NOT NULL, ADD email_notif_timeslot_reminder TINYINT(1) NOT NULL');

        $this->addSql('UPDATE user SET email_notif_cycle_start = true, email_notif_timeslot_reminder = true');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP email_notif_cycle_start, DROP email_notif_timeslot_reminder');
    }
}
