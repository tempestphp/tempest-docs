<?php

namespace App\Support\StoredEvents;

use DateTimeImmutable;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;
use Tempest\Reflection\ClassReflector;

final class StoredEvent implements DatabaseModel
{
    use IsDatabaseModel;

    public function __construct(
        public string $uuid,
        public string $eventClass,
        public string $payload,
        public DateTimeImmutable $createdAt = new DateTimeImmutable(),
    ) {}

    public function getEvent(): object
    {
        return new ClassReflector($this->eventClass)->callStatic('unserialize', $this->payload);
    }
}