<?php

use App\Support\StoredEvents\StoredEventMiddleware;
use Tempest\EventBus\EventBusConfig;

return new EventBusConfig(
    middleware: [
        StoredEventMiddleware::class,
    ],
);
