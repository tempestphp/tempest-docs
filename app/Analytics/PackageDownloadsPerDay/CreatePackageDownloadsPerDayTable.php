<?php

namespace App\Analytics\PackageDownloadsPerDay;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreatePackageDownloadsPerDayTable implements DatabaseMigration
{
    public string $name = '2024-12-14_01_create_package_downloads_per_day_table';

    public function up(): QueryStatement|null
    {
        return CreateTableStatement::forModel(PackageDownloadsPerDay::class)
            ->primary()
            ->datetime('date')
            ->varchar('package')
            ->integer('count')
            ->unique('date', 'package');
    }

    public function down(): QueryStatement|null
    {
        return DropTableStatement::forModel(PackageDownloadsPerDay::class);
    }
}