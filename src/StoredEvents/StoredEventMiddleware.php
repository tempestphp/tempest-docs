<?php

namespace App\StoredEvents;

use DateTimeImmutable;
use Tempest\EventBus\EventBusMiddleware;
use Tempest\EventBus\EventBusMiddlewareCallable;

final readonly class StoredEventMiddleware implements EventBusMiddleware
{
    #[\Override]
    public function __invoke(string|object $event, EventBusMiddlewareCallable $next): void
    {
        if ($event instanceof ShouldBeStored) {
            new StoredEvent(
                uuid: $event->uuid,
                eventClass: $event::class,
                payload: $event->serialize(),
                createdAt: ($event instanceof HasCreatedAtDate) ? $event->createdAt : new DateTimeImmutable(),
            )->save();
        }

        $next($event);
    }
}
