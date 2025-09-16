<?php

namespace App\Web\Analytics\PackageDownloadsPerDay;

use Override;
use App\Web\Analytics\PackageDownloadsPerHour\PackageDownloadsPerHour;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\AlterTableStatement;
use Tempest\Database\QueryStatements\IntegerStatement;

final class UpdatePackageDownloadsPerDayTable implements \Tempest\Database\MigratesUp
{
    public string $name = '2024-12-14_03_update_package_downloads_per_day_table';

    #[Override]
    public function up(): QueryStatement
    {
        return AlterTableStatement::forModel(PackageDownloadsPerDay::class)
            ->add(new IntegerStatement('total', default: 0));
    }
}
