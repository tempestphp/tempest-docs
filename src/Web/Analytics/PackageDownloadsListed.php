<?php

namespace App\Web\Analytics;

use App\StoredEvents\ShouldBeStored;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class PackageDownloadsListed implements ShouldBeStored
{
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

    #[\Override]
    public function serialize(): string
    {
        return json_encode([
            'uuid' => $this->uuid,
            'date' => $this->date->format('c'),
            'package' => $this->package,
            'monthly' => $this->monthly,
            'daily' => $this->daily,
            'total' => $this->total,
        ]);
    }

    #[\Override]
    public static function unserialize(string $payload): self
    {
        $data = json_decode($payload, true);

        $self = new self(
            package: $data['package'],
            date: new DateTimeImmutable($data['date']),
            monthly: $data['monthly'],
            daily: $data['daily'],
            total: $data['total'],
        );

        $self->uuid = $data['uuid'];

        return $self;
    }
}
