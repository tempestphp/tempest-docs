---
title: Event bus
description: "Tempest comes with a built-in event bus, which can be used to dispatch events throughout your application."
---

## Events and handlers

Events themselves are simple data classes. They don't have to implement anything:

```php
// app/UserCreated.php

final readonly class UserCreated
{
    public function __construct(
        public string $name,
    ) {}
}
```

Just like controller actions and console commands, event handlers are discovered automatically: every method tagged with `#[EventHandler]` will be registered as one. The event this method accepts is determined by the parameter type.

```php
// app/CreateUserCommand.php

use Tempest\Container\Singleton;
use Tempest\Console\ConsoleCommand;
use Tempest\EventBus\EventHandler;

#[Singleton]
final class CreateUserCommand
{
    #[ConsoleCommand('create:user')]
    public function __invoke(string $name): void
    {
        // …
    }

    #[EventHandler]
    public function onUserCreated(UserCreated $userCreated): void
    {
        $this->console->writeln("- {$userCreated->name}");
    }
}
```

Note that handler method names can be anything: invokable methods, `handleUserCreated()`, `onUserCreated()`, `whateverYouWant()`, …

Alternatively, event listeners can be registered as closures on the `EventBus` instance:

```php
use Tempest\Console\ConsoleCommand;

#[ConsoleCommand(name: 'users:sync')]
public function __invoke(): void
{
	$this->eventBus->listen(UserCreated::class, function (UserCreated $event) {
		$this->console->info("User {$event->name} created.");
	});

	$this->synchronizeUsers();
}
```

Triggering an event can be done with the `event()` function:

```php
use function Tempest\event;

event(new UserCreated($name));
```

Alternatively to using the `event()` function, you can inject the `EventBus`, and dispatch commands like so:

```php
// app/UserController.php

use Tempest\EventBus\EventBus;

final readonly class UserController
{
    public function __construct(
        private EventBus $eventBus,
    ) {}

    public function create(): Response
    {
        // …

        $this->eventBus->dispatch(new UserCreated($name));
    }
}
```

## Named events

Sometimes you want to broadcast an event to indicated that something happened, without attaching any data (and thus no object) to it. You can do exactly that as well:

```php
use function Tempest\event;

event('custom_event_happened');
```

Listening for such an event can be done like so:

```php
// app/ProjectHandlers.php

use Tempest\EventBus\EventHandler;

final class ProjectHandlers
{
    #[EventHandler('custom_event_happened')]
    public function onSomethingHappened(): void
    {
        // …
    }
}
```

Note that, while hardcoded strings are available, it's highly recommended to use enums as event names:

```php
enum CustomEvent
{
    case HAPPENED;
}
```

```php
use function Tempest\event;
use Tempest\EventBus\EventHandler;

event(CustomEvent::HAPPENED);

final class ProjectHandlers
{
    #[EventHandler(CustomEvent::HAPPENED)]
    public function onSomethingHappened(): void
    {
        // …
    }
}
```

## Eventbus Middleware

Whenever events are dispatched, they are passed to the eventbus, which will pass the event along to each of its handlers. Similar to web requests and console commands, this eventbus supports middleware. Eventbus middleware can be used to, for example, do logging for specific events, add metadata to events, or anything else. Eventbus middleware are classes that implement the `EventbusMiddleware` interface, and look like this:

```php
// app/MyEventBusMiddleware.php

use Tempest\EventBus\EventBusMiddleware;
use Tempest\EventBus\EventBusMiddlewareCallable;

class MyEventBusMiddleware implements EventBusMiddleware
{
    public function __construct(
        private Logger $logger,
    ) {}

    public function __invoke(object $event, EventBusMiddlewareCallable $next): void
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
