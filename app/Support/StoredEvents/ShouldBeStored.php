<?php

namespace App\Support\StoredEvents;

interface ShouldBeStored
{
    public string $uuid {
        get;
    }

    public function serialize(): string;

    public static function unserialize(string $payload): self;
}
