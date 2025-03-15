<?php

namespace App\StoredEvents;

final class StoredEventConfig
{
    public function __construct(
        /** @var class-string<\Tempest\StoredEvents\Projector> $projectors */
        public array $projectors = [],
    ) {
    }
}
