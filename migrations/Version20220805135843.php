<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805135843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_log ADD created_by_id INT DEFAULT NULL, CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE commitment_log ADD CONSTRAINT FK_EA5887CAB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_EA5887CAB03A8386 ON commitment_log (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commitment_log DROP FOREIGN KEY FK_EA5887CAB03A8386');
        $this->addSql('DROP INDEX IDX_EA5887CAB03A8386 ON commitment_log');
        $this->addSql('ALTER TABLE commitment_log DROP created_by_id, CHANGE nb_hour nb_hour NUMERIC(10, 1) DEFAULT \'0.0\' NOT NULL');
    }
}
