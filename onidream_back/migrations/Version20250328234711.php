<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328234711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between Excerpts and Categories';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categories_excerpts (categories_id INT NOT NULL, excerpts_id INT NOT NULL, PRIMARY KEY(categories_id, excerpts_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C7A5F39A21214B7 ON categories_excerpts (categories_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C7A5F39CE72C4D8 ON categories_excerpts (excerpts_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories_excerpts ADD CONSTRAINT FK_3C7A5F39A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories_excerpts ADD CONSTRAINT FK_3C7A5F39CE72C4D8 FOREIGN KEY (excerpts_id) REFERENCES excerpts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories_excerpts DROP CONSTRAINT FK_3C7A5F39A21214B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories_excerpts DROP CONSTRAINT FK_3C7A5F39CE72C4D8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categories_excerpts
        SQL);
    }
}
