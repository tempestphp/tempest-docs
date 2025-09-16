<?php

namespace App\Web\Analytics\PackageDownloadsPerHour;

use Override;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreatePackageDownloadsPerHourTable implements \Tempest\Database\MigratesUp, \Tempest\Database\MigratesDown
{
    public string $name = '2024-12-14_01_create_package_downloads_per_hour_table';
    #[Override]
    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(PackageDownloadsPerHour::class)
            ->primary()
            ->datetime('date')
            ->varchar('package')
            ->integer('count')
            ->unique('date', 'package');
    }
    #[Override]
    public function down(): QueryStatement
    {
        return DropTableStatement::forModel(PackageDownloadsPerHour::class);
    }
}
