<?php

use App\StoredEvents\StoredEventMiddleware;
use Tempest\EventBus\EventBusConfig;

return new EventBusConfig(
    middleware: [
        StoredEventMiddleware::class,
    ],
);
