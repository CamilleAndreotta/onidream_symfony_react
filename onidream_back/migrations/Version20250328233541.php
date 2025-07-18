<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328233541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relations betweens Authors and Books';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE authors_books (authors_id INT NOT NULL, books_id INT NOT NULL, PRIMARY KEY(authors_id, books_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_793B48D8CBBCF7F5 ON authors_books (authors_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_793B48D87DD8AC20 ON authors_books (books_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books ADD CONSTRAINT FK_793B48D8CBBCF7F5 FOREIGN KEY (authors_id) REFERENCES authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books ADD CONSTRAINT FK_793B48D87DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books DROP CONSTRAINT FK_793B48D8CBBCF7F5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books DROP CONSTRAINT FK_793B48D87DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE authors_books
        SQL);
    }
}
