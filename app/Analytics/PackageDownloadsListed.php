<?php

namespace App\Analytics;

use App\Support\StoredEvents\IsStoredEvent;
use App\Support\StoredEvents\ShouldBeStored;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class PackageDownloadsListed implements ShouldBeStored
{
    use IsStoredEvent;

    public string $uuid;

    public function __construct(
        public string $package,
        public DateTimeImmutable $date,
        public int $monthly,
        public int $daily,
        public int $total,
    ) {
        $this->uuid = Uuid::v4()->toString();
    }
}