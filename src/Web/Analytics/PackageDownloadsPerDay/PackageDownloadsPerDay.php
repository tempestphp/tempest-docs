<?php

namespace App\Web\Analytics\PackageDownloadsPerDay;

use DateTimeImmutable;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

final class PackageDownloadsPerDay implements DatabaseModel
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
