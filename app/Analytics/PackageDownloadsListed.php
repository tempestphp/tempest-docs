<?php

namespace App\Analytics;

use App\Support\StoredEvents\ShouldBeStored;
use Symfony\Component\Uid\Uuid;

final readonly class PackageDownloadsListed implements ShouldBeStored
{
    public string $uuid;

    public function __construct(
        public string $package,
        public int $monthly,
        public int $daily,
        public int $total,
    ) {
        $this->uuid = Uuid::v4()->toString();
    }

    public function serialize(): string
    {
        return json_encode([
            'uuid' => $this->uuid,
            'package' => $this->package,
            'monthly' => $this->monthly,
            'daily' => $this->daily,
            'total' => $this->total,
        ]);
    }

    public static function unserialize(string $payload): self
    {
        $data = json_decode($payload, true);

        $self = new self(
            package: $data['package'],
            monthly: $data['monthly'],
            daily: $data['daily'],
            total: $data['total'],
        );

        $self->uuid = $data['uuid'];

        return $self;
    }
}