---
title: Introduction
description: "Tempest is a PHP framework designed to get out of your way. Its core philosophy is to enable developers to write as little framework-specific code as possible, so that they can focus on application code instead."
---

Tempest makes writing PHP applications pleasant thanks to its thoughtfully designed sprinkles of quality-of-life features.

It embraces modern PHP syntax in its implementation of routing, ORM, console commands, messaging, logging, it takes inspiration from the best front-end frameworks for its templating engine syntax, and provides unique capabilities amongst other framework, such as [discovery](../3-internals/02-discovery), to improve developer experience.

You may also be interested in reading how it has an [unfair advantage](/blog/unfair-advantage) over other frameworks.

Code says more than words, so here's what a controller look like when using Tempest:

```php app/BookController.php
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

And here's a Tempest console command:

```php app/MigrateUpCommand.php
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
    public function __invoke(): void
    {
        $this->migrationManager->up();

        $this->console->success("Everything migrated");
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $migrationMigrated): void
    {
        $this->console->writeln("- {$migrationMigrated->name}");
    }
}
```

:::warning Ready to give it a try?
Keep on reading and consider [**giving Tempest a star️ on GitHub**](https://github.com/tempestphp/tempest-framework). If you want to be part of the community, you can [**join our Discord server**](https://discord.gg/pPhpTGUMPQ), and if you feel like contributing, you can check out our [contributing guide](/docs/internals/contributing)!
:::
