<?php

namespace App\Support\StoredEvents;

use Tempest\Mapper\MapTo;
use function Tempest\map;

/**
 * @phpstan-require-implements \App\Support\StoredEvents\StoredEvent
 */
trait IsStoredEvent
{
    public function serialize(): string
    {
        return map($this)->to(MapTo::JSON);
    }

    public static function unserialize(string $payload): self
    {
        return map($payload)->to(self::class);
    }
}