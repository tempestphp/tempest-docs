<?php

namespace App\Web\Analytics\PackageDownloadsPerHour;

use DateTimeImmutable;
use Tempest\Database\IsDatabaseModel;

final class PackageDownloadsPerHour
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
