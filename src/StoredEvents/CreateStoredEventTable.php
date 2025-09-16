<?php

namespace App\StoredEvents;

use Override;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;

final class CreateStoredEventTable implements \Tempest\Database\MigratesUp
{
    public string $name {
        get => '00-00-0000-create_stored_events_table';
    }

    #[Override]
    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(StoredEvent::class)
            ->primary()
            ->text('uuid')
            ->text('eventClass')
            ->text('payload')
            ->datetime('createdAt');
    }
}
