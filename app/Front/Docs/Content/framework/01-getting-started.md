---
title: Getting Started
---

**Tempest is a PHP framework that gets out of your way**. Its design philosophy is that developers should write as little framework-related code as possible, so that they can **focus on application code** instead.

Tempest embraces **modern PHP syntax**, covers a wide range of features: routing, MVC, ORM and database, rich console applications, events and commands, logging, a modern view engine, and unique capabilities such as [discovery](#project-structure) to improve developer experience.

Tempest can be installed **as a standalone PHP project**, as well as **a package within existing projects**. The framework modules — like, for example, `tempest/console` or `tempest/event-bus` — can also be installed **individually**, including in projects built on other frameworks.

Since code says more than words, here's a Tempest controller:

```php
// app/BookController.php

use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Response;
use Tempest\Router\Responses\Ok;
use Tempest\Router\Responses\Redirect;

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

        return new Redirect([self::class, 'show'], book: $book->id);
    }
    
    // …
}
```

And here's a Tempest console command:

```php
// app/MigrateUpCommand.php

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

Ready to give it a try? Keep on reading, [**give Tempest a star️ on GitHub**](https://github.com/tempestphp/tempest-framework). If you want to be part of the community, you can [**join our Discord server**](https://discord.gg/pPhpTGUMPQ), and you can check out our [contributing guide](/docs/internals/contributing)!

## Installation

You can install Tempest in two ways: as a web app with a basic frontend bootstrap, or by requiring the framework as a package in any project you'd like — these can be projects built on top of other frameworks.

### A standalone Tempest app

If you want to start a new Tempest project, you can use `tempest/app` as the starting point. Use `composer create-project` to start:

```txt
composer create-project tempest/app my-app --stability alpha
cd my-app
```

The project scaffold includes a basic frontend setup including tailwind:

```txt
npm run dev
```

You can access your app by using PHP's built-in server.

```text
./tempest serve
PHP 8.3.3 Development Server (http://localhost:8000) started
```

### Tempest as a package

If you don't need an app scaffold, you can opt to install `tempest/framework` as a standalone package. You could do this in any project; it could already contain code, or it could be an empty project.

```txt
composer require tempest/framework:1.0-alpha.4
```

Installing Tempest this way will give you access to the tempest console as a composer binary:

```txt
./vendor/bin/tempest
```

Optionally, you can choose to install Tempest's entry points in your project:

```txt
./vendor/bin/tempest install framework
```

Installing Tempest into a project means copying one or more of these files into that project:

- `public/index.php` — the web application entry point
- `tempest` – the console application entry point
- `.env.example` – a clean example of a `.env` file 
- `.env` – the real environment file for your local installation 

You can choose which files you want to install, and you can always rerun the `install` command at a later point in time.

## Project structure

Tempest won't impose any file structure on you: one of its core features is that it will scan all project and package code for you, and it will automatically discover any files the framework needs to know about. For example: Tempest is able to differentiate between a controller method and a console command by looking at the code, instead of relying on naming conventions or verbose configuration files. This concept is called **discovery**, and it's one of Tempest's most powerful features.

For example, you can make a project that looks like this:

```txt
app
├── Console
│   └── RssSyncCommand.php
├── Controllers
│   ├── BlogPostController.php
│   └── HomeController.php
└── Views
    ├── blog.view.php
    └── home.view.php
```

Or a project that looks like this:

```txt
app
├── Blog
│   ├── BlogPostController.php
│   ├── RssSyncCommand.php
│   └── blog.view.php
└── Home
    ├── HomeController.php
    └── home.view.php
```

From Tempest's perspective, it's all the same.

Discovery works by scanning your project code, and looking at each file and method individually to determine what that code does. For production apps, Tempest will cache the discovery process, so there's no performance overhead that comes with it.

As an example, Tempest is able to determine which methods are controller methods based on their route attributes:

```php
// app/BlogPostController.php

use Tempest\Router\Get;
use Tempest\Router\Response;
use Tempest\View\View;

final readonly class BlogPostController
{
    #[Get('/blog')]
    public function index(): View
    { /* … */ }
    
    #[Get('/blog/{post}')]
    public function show(Post $post): Response
    { /* … */ }
}
```

And likewise, it's able to detect console commands based on their console command attribute:

```php
// src/RssSyncCommand.php

use Tempest\Console\HasConsole;
use Tempest\Console\ConsoleCommand;

final readonly class RssSyncCommand
{
    use HasConsole;

    #[ConsoleCommand('rss:sync')]
    public function __invoke(bool $force = false): void  
    { /* … */ }
}
```

## Discovery in production

While discovery is a really powerful feature, it also comes with some performance considerations. In production environments, you want to make sure that the discovery workflow is cached. That's done like this:

```env
{:hl-comment:# .env:}
{:hl-property:DISCOVERY_CACHE:}={:hl-keyword:true:}
```

What's important though, is that production discovery cache will also need to be pre-generated. You can do this by running the `discovery:generate` command:

```console
~ ./tempest discovery:generate
<em>Clearing existing discovery cache…</em>
<success>Discovery cached has been cleared</success>
<em>Generating new discovery cache… (cache strategy used: all)</em>
<success>Done</success> 1114 items cached
```

In other words: it's best that you include the `discovery:generate` command in your deployment pipeline. Make sure to run it before you run any other Tempest commands.

## Discovery for local development

By default, discovery cache will be disabled in local development. Depending on your local setup, it's likely that you won't run into noticeable slowdowns. However, for larger projects, you might benefit from enabling _partial discovery cache_:

```env
{:hl-comment:# .env:}
{:hl-property:DISCOVERY_CACHE:}={:hl-keyword:partial:}
```

This caching strategy will only cache discovery for vendor files. Keep in mind that you will also have to generate the discovery cache:

```console
~ ./tempest discovery:generate
<em>Clearing existing discovery cache…</em>
<success>Discovery cached has been cleared</success>
<em>Generating new discovery cache… (cache strategy used: partial)</em>
<success>Done</success> 111 items cached
```

If you're using partial discovery cache, it is recommended to automatically run `discovery:generate` after every composer update: 

```json
{:hl-comment:// …:}

"scripts": {
    "post-package-update": [
        "php tempest discovery:generate"
    ]
}
```

Note that, if you've created your project using `tempest/app`, you'll have the `post-package-update` script already included. If you want to, you can read the [internal documentation about discovery](/docs/internals/02-discovery) to learn more.
