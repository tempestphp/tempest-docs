<?php

namespace App\Analytics;

use App\Support\StoredEvents\HasCreatedAtDate;
use App\Support\StoredEvents\IsStoredEvent;
use App\Support\StoredEvents\ShouldBeStored;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class PageVisited implements ShouldBeStored, HasCreatedAtDate
{
    use IsStoredEvent;

    public string $uuid;

    public function __construct(
        public string $url,
        public DateTimeImmutable $visitedAt,
        public string $ip,
        public string $userAgent,
        public string $raw,
    ) {
        $this->uuid = Uuid::v4()->toString();
    }

    public DateTimeImmutable $createdAt {
        get => $this->visitedAt;
    }
}