<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404090548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create todo table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE todo (
                id UUID PRIMARY KEY,
                name VARCHAR(255) NOT NULL
              );
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE todo;
        SQL
        );
    }
}
