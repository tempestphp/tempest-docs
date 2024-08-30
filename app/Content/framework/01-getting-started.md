---
title: Getting Started
---

**Tempest is a PHP framework that gets out of your way**. Its design philosophy is that developers should write as little framework-related code as possible, so that they can **focus on application code** instead.

Tempest embraces **modern PHP syntax** (`^8.4`), covers a wide range of features: routing, MVC, ORM and database, rich console applications, events and commands, logging, a modern view engine, and unique capabilities such as [discovery](#content-a-basic-tempest-project) to improve developer experience.

Tempest can be installed **as a standalone PHP project**, as well as **a package within existing projects**. The framework modules — like, for example, `tempest/console` or `tempest/event-bus` — can also be installed **individually**, including in projects built on other frameworks.

Since code says more than words, here's a Tempest controller:

```php
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

        $this->success("Everything migrated");
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $migrationMigrated): void
    {
        $this->console->writeln("- {$migrationMigrated->name}");
    }
}
```

Ready to give it a try? [**Give Tempest a star️ on GitHub**](https://github.com/tempestphp/tempest-framework) and [**join our Discord server**](https://discord.gg/pPhpTGUMPQ)!

## Installation

You can install Tempest in two ways: as a web app with a basic frontend bootstrap, or by requiring the framework as a package in any project you'd like — these can be projects built on top of other frameworks.

### A standalone Tempest app

If you want to start a new Tempest project, you can use `tempest/app` as the starting point. Use `composer create-project` to start:

```txt
composer create-project tempest/app my-app
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
composer require tempest/framework
```

Installing Tempest this way will give you access to the tempest console as a composer binary:

```txt
./vendor/bin/tempest
```

Optionally, you can choose to install Tempest's entry points in your project:

```txt
./vendor/bin/tempest install
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

Discovery works by scanning you project code, and looking at each file and method individually to determine what that code does. For production apps, Tempest will cache the discovery process, so there's no performance overhead that comes with it.

As an example, Tempest is able to determine which methods are controller methods based on their route attributes:

```php
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
final readonly class RssSyncCommand
{
    public function __construct(private Console $console) {}

    #[ConsoleCommand('rss:sync')]
    public function __invoke(bool $force = false): void  
    { /* … */ }
}
```

If you want to, you can read the [internal documentation about discovery](/internals/02-discovery) to learn more. 