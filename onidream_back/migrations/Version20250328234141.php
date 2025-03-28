<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328234141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between Books and Editors and Excerpts';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE books ADD editor_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books ADD CONSTRAINT FK_4A1B2A926995AC4C FOREIGN KEY (editor_id) REFERENCES editors (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A1B2A926995AC4C ON books (editor_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE excerpts ADD books_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE excerpts ADD CONSTRAINT FK_EA84F6007DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EA84F6007DD8AC20 ON excerpts (books_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE excerpts DROP CONSTRAINT FK_EA84F6007DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EA84F6007DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE excerpts DROP books_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP CONSTRAINT FK_4A1B2A926995AC4C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A1B2A926995AC4C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP editor_id
        SQL);
    }
}
