<?php

namespace App\Web\Analytics\VisitsPerDay;

use Override;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;

final class AlterVisitsPerDayTable implements \Tempest\Database\MigratesUp
{
    public string $name = '2024-12-13_01_alter_visits_table';

    #[Override]
    public function up(): QueryStatement
    {
        return AlterTableStatement::forModel(VisitsPerDay::class)->unique('date');
    }
}
