<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721114425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__quote AS SELECT id, author_id, original FROM quote');
        $this->addSql('DROP TABLE quote');
        $this->addSql('CREATE TABLE quote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, original VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_6B71CBF4F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO quote (id, author_id, original) SELECT id, author_id, original FROM __temp__quote');
        $this->addSql('DROP TABLE __temp__quote');
        $this->addSql('CREATE INDEX IDX_6B71CBF4F675F31B ON quote (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_6B71CBF4F675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__quote AS SELECT id, author_id, original FROM quote');
        $this->addSql('DROP TABLE quote');
        $this->addSql('CREATE TABLE quote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, original VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO quote (id, author_id, original) SELECT id, author_id, original FROM __temp__quote');
        $this->addSql('DROP TABLE __temp__quote');
    }
}
