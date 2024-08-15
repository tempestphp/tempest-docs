---
title: Events
---

Tempest comes with a built-in event bus. Just like controller actions and console commands, event handlers are discovered automatically:

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
    public function onMigrationMigrated(MigrationMigrated $event): void
    {
        $this->console->writeln("- {$event->name}");
        $this->count += 1;
    }
}
```

Events themselves are simple data classes. They don't have to implement anything:

```php
final readonly class MigrationMigrated
{
    public function __construct(
        public string $name,
    ) {}
}
```

Triggering an event can be done with the `event()` function:

```php
event(new MigrationMigrated($name));
```