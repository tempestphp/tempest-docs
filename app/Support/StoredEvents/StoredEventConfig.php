<?php

namespace App\Support\StoredEvents;

final class StoredEventConfig
{
    public function __construct(
        /** @var class-string<\App\Support\StoredEvents\Projector> $projectors */
        public array $projectors = [],
    ) {
    }
}
