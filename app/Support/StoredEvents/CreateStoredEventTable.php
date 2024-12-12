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

    public function up(): QueryStatement|null
    {
        return CreateTableStatement::forModel(StoredEvent::class)
            ->primary()
            ->text('uuid')
            ->text('eventClass')
            ->text('payload')
            ->datetime('createdAt');
    }

    public function down(): QueryStatement|null
    {
        // TODO: Implement down() method.
    }
}