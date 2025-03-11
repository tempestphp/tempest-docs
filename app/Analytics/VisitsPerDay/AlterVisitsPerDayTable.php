<?php

namespace App\Analytics\VisitsPerDay;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;

final class AlterVisitsPerDayTable implements DatabaseMigration
{
    public string $name = '2024-12-13_01_alter_visits_table';

    #[\Override]
    public function up(): ?QueryStatement
    {
        return AlterTableStatement::forModel(VisitsPerDay::class)->unique('date');
    }

    #[\Override]
    public function down(): ?QueryStatement
    {
        return null;
    }
}
