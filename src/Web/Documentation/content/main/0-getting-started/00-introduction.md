---
title: Introduction
description: "Tempest is a PHP framework designed to get out of your way. Its core philosophy is to enable developers to write as little framework-specific code as possible, so that they can focus on application code instead."
---

Tempest makes writing PHP applications pleasant thanks to carefully crafted quality-of-life features that feel like a natural extension of vanilla PHP.

It embraces modern PHP syntax in its implementation of routing, ORM, console commands, messaging, logging, it takes inspiration from the best front-end frameworks for its templating engine syntax, and provides unique capabilities, such as [discovery](../3-internals/02-discovery), to improve developer experience.

You may be interested in reading how it has an [unfair advantage](/blog/unfair-advantage) over other frameworks—but code says more than words, so here are a few examples of code written on top of Tempest:

```php
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Response;
use Tempest\Router\Responses\Ok;
use Tempest\Router\Responses\Redirect;
use function Tempest\uri;

final readonly class BookController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response
    {
        return new Ok($book);
    }

    #[Post('/books')]
    public function store(CreateBookRequest $request): Response
    {
        $book = map($request)->to(Book::class)->save();

        return new Redirect(uri([self::class, 'show'], book: $book->id));
    }

    // …
}
```

The above snippet is an example of a controller. It features [attribute-based routes](../1-essentials/02-controllers), mapping a request to a data object using the [mapper](../2-tempest-in-depth/01-mapper), [URL generation](../1-essentials/02-controllers#generating-uris) and [dependency injection](../1-essentials/01-container#autowired-dependencies).

```php
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\Middleware\ForceMiddleware;
use Tempest\Console\Middleware\CautionMiddleware;
use Tempest\EventBus\EventHandler;

final readonly class MigrateUpCommand
{
    public function __construct(
        private Console $console,
        private MigrationManager $migrationManager,
    ) {}

    #[ConsoleCommand(
        name: 'migrate:up',
        description: 'Run all new migrations',
        middleware: [ForceMiddleware::class, CautionMiddleware::class],
    )]
    public function __invoke(bool $fresh = false): void
    {
        if ($fresh) {
            $this->migrationManager->dropAll();
            $this->console->success("Database dropped.");
        }

        $this->migrationManager->up();
        $this->console->success("Migrations applied.");
    }

    #[EventHandler]
    public function onTableDropped(TableDropped $event): void
    {
        $this->console->writeln("- Dropped {$event->name}");
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $migrationMigrated): void
    {
        $this->console->writeln("- {$migrationMigrated->name}");
    }
}
```

This is a [console command](../3-console/02-building-console-commands). Console commands can be defined in any class, as long as the {b`#[Tempest\Console\ConsoleCommand]`} attribute is used on a method. Command arguments are defined as the method's arguments, effectively removing the need to learn some specific framework syntax.

This example also shows how to [register events globally](../2-tempest-in-depth/03-events) using the {b`#[Tempest\EventBus\EventHandler]`}.

---

:::info Ready to give it a try?
Keep on reading and consider [**giving Tempest a star️ on GitHub**](https://github.com/tempestphp/tempest-framework). If you want to be part of the community, you can [**join our Discord server**](https://discord.gg/pPhpTGUMPQ), and if you feel like contributing, you can check out our [contributing guide](/docs/extra-topics/contributing)!
:::
