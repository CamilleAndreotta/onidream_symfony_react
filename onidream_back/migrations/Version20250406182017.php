<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406182017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add picture field in book table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_793b48d8cbbcf7f5 RENAME TO IDX_2DFDA3CB6DE2013A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_793b48d87dd8ac20 RENAME TO IDX_2DFDA3CB7DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books ADD picture VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_2dfda3cb7dd8ac20 RENAME TO idx_793b48d87dd8ac20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_2dfda3cb6de2013a RENAME TO idx_793b48d8cbbcf7f5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP picture
        SQL);
    }
}
