<?php

namespace App\Analytics\VisitsPerDay;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateVisitsPerDayTable implements DatabaseMigration
{
    public string $name {
        get => '2024-12-12_01_create_visits_table';
    }

    public function up(): QueryStatement|null
    {
        return CreateTableStatement::forModel(VisitsPerDay::class)
            ->primary()
            ->text('date')
            ->integer('count');
    }

    public function down(): QueryStatement|null
    {
        return DropTableStatement::forModel(VisitsPerDay::class);
    }
}