<?php

namespace App\Web\Analytics\PackageDownloadsPerDay;

use DateTimeImmutable;
use Tempest\Database\IsDatabaseModel;

final class PackageDownloadsPerDay
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
