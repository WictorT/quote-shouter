<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721103250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BDAFD8C85E237E06');
        $this->addSql('CREATE TEMPORARY TABLE __temp__author AS SELECT id, name FROM author');
        $this->addSql('DROP TABLE author');
        $this->addSql('CREATE TABLE author (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO author (id, name) SELECT id, name FROM __temp__author');
        $this->addSql('DROP TABLE __temp__author');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BDAFD8C8989D9B62 ON author (slug)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__quote AS SELECT id, content, author_id FROM quote');
        $this->addSql('DROP TABLE quote');
        $this->addSql('CREATE TABLE quote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, original VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO quote (id, original, author_id) SELECT id, content, author_id FROM __temp__quote');
        $this->addSql('DROP TABLE __temp__quote');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BDAFD8C8989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__author AS SELECT id, name FROM author');
        $this->addSql('DROP TABLE author');
        $this->addSql('CREATE TABLE author (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO author (id, name) SELECT id, name FROM __temp__author');
        $this->addSql('DROP TABLE __temp__author');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BDAFD8C85E237E06 ON author (name)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__quote AS SELECT id, original, author_id FROM quote');
        $this->addSql('DROP TABLE quote');
        $this->addSql('CREATE TABLE quote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, content VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO quote (id, content, author_id) SELECT id, original, author_id FROM __temp__quote');
        $this->addSql('DROP TABLE __temp__quote');
    }
}
