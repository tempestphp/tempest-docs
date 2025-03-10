<?php

namespace App\Analytics\VisitsPerHour;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateVisitsPerHourTable implements DatabaseMigration
{
    public string $name = '2024-12-13_01_create_visits_per_hour_table';

    #[\Override]
    public function up(): ?QueryStatement
    {
        return CreateTableStatement::forModel(VisitsPerHour::class)
            ->primary()
            ->datetime('date')
            ->integer('count')
            ->index('date');
    }

    #[\Override]
    public function down(): ?QueryStatement
    {
        return DropTableStatement::forModel(VisitsPerHour::class);
    }
}
