<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409204246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create task table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TYPE task_status AS ENUM ('TODO', 'DONE');
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE TABLE task (
                id UUID PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                status task_status NOT NULL DEFAULT 'TODO',
                todo_id UUID NOT NULL,
                FOREIGN KEY (todo_id) REFERENCES todo(id)
            );
       SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE task;
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TYPE task_status;        
        SQL
        );
    }
}
