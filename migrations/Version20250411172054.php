<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411172054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create outbox table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE outbox (
                id UUID PRIMARY KEY,
                event_type VARCHAR(255) NOT NULL,
                payload JSONB NOT NULL
            );
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE outbox
        SQL
        );
    }
}
