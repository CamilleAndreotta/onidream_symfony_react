<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406184948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Users table and add relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id SERIAL NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_books (users_id INT NOT NULL, books_id INT NOT NULL, PRIMARY KEY(users_id, books_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AD6C8EDB67B3B43D ON users_books (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AD6C8EDB7DD8AC20 ON users_books (books_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_excerpts (users_id INT NOT NULL, excerpts_id INT NOT NULL, PRIMARY KEY(users_id, excerpts_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_91C5DDDA67B3B43D ON users_excerpts (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_91C5DDDACE72C4D8 ON users_excerpts (excerpts_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_books ADD CONSTRAINT FK_AD6C8EDB67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_books ADD CONSTRAINT FK_AD6C8EDB7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_excerpts ADD CONSTRAINT FK_91C5DDDA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_excerpts ADD CONSTRAINT FK_91C5DDDACE72C4D8 FOREIGN KEY (excerpts_id) REFERENCES excerpts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors ADD users_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors ADD CONSTRAINT FK_8E0C2A5167B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8E0C2A5167B3B43D ON authors (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories ADD users_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories ADD CONSTRAINT FK_3AF3466867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3AF3466867B3B43D ON categories (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editors ADD users_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editors ADD CONSTRAINT FK_3076646867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3076646867B3B43D ON editors (users_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors DROP CONSTRAINT FK_8E0C2A5167B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories DROP CONSTRAINT FK_3AF3466867B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editors DROP CONSTRAINT FK_3076646867B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_books DROP CONSTRAINT FK_AD6C8EDB67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_books DROP CONSTRAINT FK_AD6C8EDB7DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_excerpts DROP CONSTRAINT FK_91C5DDDA67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_excerpts DROP CONSTRAINT FK_91C5DDDACE72C4D8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_books
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_excerpts
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3076646867B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editors DROP users_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8E0C2A5167B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors DROP users_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3AF3466867B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categories DROP users_id
        SQL);
    }
}
