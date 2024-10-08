---
title: The Commandbus
---

Tempest comes with a built-in command bus, which can be used to dispatch a command to its handler. A commandbus can offer multiple advantages over a more direct approach to modelling processes: commands and their handlers can easily be tested in isolation, they are simple to serialize, and similar to the eventbus, the commandbus also supports middleware.

## Commands and handlers

Commands themselves are simple data classes. They don't have to implement anything:

```php
final readonly class CreateUser
{
    public function __construct(
        public string $name,
        public string $email,
        public string $passwordHash,
    ) {}
}
```

Just like controller actions and console commands, command handlers are discovered automatically: every method tagged with `#[CommandHandler]` will be registered as one. Tempest knows which command a method handles by looking at the type of the first parameter:

```php
final class UserHandlers
{
    #[CommandHandler]
    public function handleCreateUser(CreateUser $createUser): void
    {
        User::create(
            name: $createUser->name,
            email: $createUser->email,
            password: $createUser->passwordHash,
        );
        
        // Send mail…
    }
}
```

Note that handler method names can be anything: invokable methods, `handleCreateUser()`, `handleCreateUser()`, `whateverYouWant()`, …

Dispatching a command can be done with the `command()` function:

```php
command(new CreateUser($name));
```

Alternatively to using the `command()` function, you can inject the `CommandBus`, and dispatch commands like so:

```php
use Tempest\CommandBus\CommandBus;

final readonly class UserController()
{
    public function __construct(
        private CommandBus $commandBus,
    ) {}
    
    public function create(): Response
    {
        // …
        
        $this->commandBus->dispatch(new CreateUser($name));
    }
}
```

## Commandbus Middleware

Whenever commands are dispatched, they are passed to the commandbus, which will pass the command along to each of its handlers. Similar to web requests and console commands, this commandbus supports middleware. Commandbus middleware can be used to, for example, do logging for specific commands, add metadata to commands, or anything else. Commandbus middleware are classes that implement the `CommandbusMiddleware` interface, and look like this:

```php
class MyCommandBusMiddleware implements CommandBusMiddleware
{
    public function __construct(
        private Logger $logger,
    ) {}

    public function __invoke(object $command, callable $next): void
    {
        $next($command);
        
        if ($command instanceof ShouldBeLogged) {
            $this->logger->info($command->getLogMessage());
        }
    }
}
```

Note that commandbus middleware **is not discovered automatically**. This is because in many cases you'll need fine-grained control over the order of how middleware is executed, which is the same for HTTP and console middleware. In order to register your middleware classes, you must register them via `CommandBusConfig`:

```php
// app/Config/events.php

use Tempest\CommandBus\CommandBusConfig;

return new CommandBusConfig(
    // …
    
    middleware: [
        MyCommandBusMiddleware::class,
    ],
);
```