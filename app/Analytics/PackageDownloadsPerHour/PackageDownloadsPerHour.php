<?php

namespace App\Analytics\PackageDownloadsPerHour;

use DateTimeImmutable;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

final class PackageDownloadsPerHour implements DatabaseModel
{
    use IsDatabaseModel;

    public function __construct(
        public DateTimeImmutable $date,
        public string $package,
        public int $count,
        public int $total,
    ) {
    }
}
