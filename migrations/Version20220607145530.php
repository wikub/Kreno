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
final class Version20220607145530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de l\'attribut enabled à timeslot_template';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeslot_template ADD enabled TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE timeslot_template SET enabled = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeslot_template DROP enabled');
    }
}
