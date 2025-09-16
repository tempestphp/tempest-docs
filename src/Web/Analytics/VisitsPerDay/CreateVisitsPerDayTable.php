<?php

namespace App\Web\Analytics\VisitsPerDay;

use Override;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateVisitsPerDayTable implements \Tempest\Database\MigratesUp, \Tempest\Database\MigratesDown
{
    public string $name = '2024-12-12_01_create_visits_table';
    #[Override]
    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(VisitsPerDay::class)
            ->primary()
            ->text('date')
            ->integer('count');
    }
    #[Override]
    public function down(): QueryStatement
    {
        return DropTableStatement::forModel(VisitsPerDay::class);
    }
}
