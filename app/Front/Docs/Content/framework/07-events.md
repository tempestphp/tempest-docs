---
title: The Eventbus
---

Tempest comes with a built-in event bus, which can be used to dispatch events throughout your application.

## Events and handlers

Events themselves are simple data classes. They don't have to implement anything:

```php
final readonly class MigrationMigrated
{
    public function __construct(
        public string $name,
    ) {}
}
```

Just like controller actions and console commands, event handlers are discovered automatically: every method tagged with `#[EventHandler]` will be registered as one. The event this method accepts is determined by the parameter type.

```php
#[Singleton]
final class MigrateUpCommand
{
    private int $count = 0;

    #[ConsoleCommand('migrate:up')]
    public function __invoke(): void
    {
        // …
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $migrationMigrated): void
    {
        $this->console->writeln("- {$migrationMigrated->name}");
        $this->count += 1;
    }
}
```

Note that handler method names can be anything: invokable methods, `handleMigrationMigrated()`, `onMigrationMigrated()`, `whateverYouWant()`, …

Triggering an event can be done with the `event()` function:

```php
event(new MigrationMigrated($name));
```

Whenever an event is triggered, all its handlers will be resolved and executed.

## Eventbus Middleware

Whenever events are dispatched, they are passed to the eventbus, which will pass the event along to each of its handlers. Similar to web requests and console commands, this eventbus supports middleware. Eventbus middleware can be used to, for example, do logging for specific events, add metadata to events, or anything else. Eventbus middleware are classes that implement the `EventbusMiddleware` interface, and look like this:

```php
class MyEventBusMiddleware implements EventBusMiddleware
{
    public function __construct(
        private Logger $logger,
    ) {}

    public function __invoke(object $event, callable $next): void
    {
        $next($event);
        
        if ($event instanceof ShouldBeLogged) {
            $this->logger->info($event->getLogMessage());
        }
    }
}
```

Note that eventbus middleware **is not discovered automatically**. This is because in many cases you'll need fine-grained control over the order of how middleware is executed, which is the same for HTTP and console middleware. In order to register your middleware classes, you must register them via `EventBusConfig`:

```php
// app/Config/events.php

use Tempest\EventBus\EventBusConfig;

return new EventBusConfig(
    // …
    
    middleware: [
        MyEventBusMiddleware::class,
    ],
);
```

## Built-in framework events

Tempest already uses a handful of internal events, although they aren't properly documented yet. We plan no added a variety of framework-specific events that users can hook into in [version 1.1](https://github.com/tempestphp/tempest-framework/issues/268).