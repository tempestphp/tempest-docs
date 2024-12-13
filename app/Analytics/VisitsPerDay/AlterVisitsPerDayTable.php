<?php

namespace App\Analytics\VisitsPerDay;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;

final class AlterVisitsPerDayTable implements DatabaseMigration
{
    public string $name {
        get => '2024-12-13_01_alter_visits_table';
    }

    public function up(): QueryStatement|null
    {
        return AlterTableStatement::forModel(VisitsPerDay::class)->unique('date');
    }

    public function down(): QueryStatement|null
    {
        return null;
    }
}