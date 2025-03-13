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

    #[\Override]
    public function up(): ?QueryStatement
    {
        return AlterTableStatement::forModel(PackageDownloadsPerDay::class)
            ->add(new IntegerStatement('total', default: 0));
    }

    #[\Override]
    public function down(): ?QueryStatement
    {
        return null;
    }
}
