<?php

namespace App\Support\StoredEvents;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;

final class CreateStoredEventTable implements DatabaseMigration
{
    public string $name {
        get => '00-00-0000-create_stored_events_table';
    }

    #[\Override]
    public function up(): ?QueryStatement
    {
        return CreateTableStatement::forModel(StoredEvent::class)
            ->primary()
            ->text('uuid')
            ->text('eventClass')
            ->text('payload')
            ->datetime('createdAt');
    }

    #[\Override]
    public function down(): ?QueryStatement
    {
        // @mago-expect comment/no-untagged-todo
        // TODO: Implement down() method.
    }
}
