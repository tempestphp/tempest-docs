<?php

declare(strict_types=1);

namespace App\StoredEvents;

final class StoredEventConfig
{
    public function __construct(
        /** @var class-string<Projector> $projectors */
        public array $projectors = [],
    ) {}
}
