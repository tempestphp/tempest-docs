<?php

namespace App\Analytics\PackageDownloadsPerDay;

use App\Analytics\PackageDownloadsPerHour\PackageDownloadsPerHour;
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;
use Tempest\Database\QueryStatements\IntegerStatement;

final class UpdatePackageDownloadsPerDayTable implements DatabaseMigration
{
    public string $name = '2024-12-14_03_update_package_downloads_per_day_table';

    public function up(): QueryStatement|null
    {
        return AlterTableStatement::forModel(PackageDownloadsPerDay::class)
            ->add(new IntegerStatement('total', default: 0));
    }

    public function down(): QueryStatement|null
    {
        return null;
    }
}