<?php

namespace App\Analytics\PackageDownloadsPerHour;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;
use Tempest\Database\QueryStatements\IntegerStatement;

final class UpdatePackageDownloadsPerHourTable implements DatabaseMigration
{
    public string $name = '2024-12-14_02_update_package_downloads_per_hour_table';

    public function up(): QueryStatement|null
    {
        return AlterTableStatement::forModel(PackageDownloadsPerHour::class)
            ->add(new IntegerStatement('total', default: 0));
    }

    public function down(): QueryStatement|null
    {
        return null;
    }
}