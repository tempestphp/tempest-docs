---
title: Getting started
description: Tempest is a PHP framework for web and console applications, designed to get out of your way. Its core philosophy is to help developers focus on their application code, without being bothered with configuring or hand-holding the framework. 
---

## Installation

Thanks to Tempest's design, you can install the whole framework or individual components, both in new projects and existing ones. It's even possible to use Tempest in existing projects, alongside other frameworks. 

Start by creating a project from scratch:

```shell
{:hl-keyword:composer:} create-project tempest/app my-app --stability beta
{:hl-keyword:cd:} my-app
```

Or by requiring tempest in an existing codebase:

```sh
{:hl-keyword:composer:} require tempest/framework:1.0-beta.1
```

If you required `tempest/framework` into an existing project, you may optionally install framework-related files:

```sh
{:hl-keyword:./vendor/bin/tempest:} install framework
```

:::info
In these docs, we'll always use `{:hl-keyword:php:} tempest` to access the Tempest console. If you didn't publish the file within your project, you can still access it via `{:hl-keyword:php:} vendor/bin/tempest`
:::

This installer will prompt you to install the following files into your project:

- `public/index.php` — the web application entry point
- `tempest` – the console application entry point
- `.env.example` – a clean example of a `.env` file
- `.env` – the real environment file for your local installation

You can choose which files you want to install, and you can always rerun the `install` command at a later point in time.

:::warning
Because Tempest is currently in beta (and still has two dev dependencies), you will need to allow dev dependencies as your minimum stability in `composer.json`: `{json}"minimum-stability": "dev",`, you should also ensure that composer still prefers stable versions if they are available: `{json}"prefer-stable": true,`. These composer properties are only necessary as long as Tempest isn't stable yet.
:::

### Installation requirements

Tempest requires [PHP 8.4+](https://www.php.net/downloads.php) and [Composer](https://getcomposer.org/) to be installed. Optionally, you may install either [Bun](https://bun.sh) or [Node](https://nodejs.org) if you chose to bundle front-end assets.

For a better experience, it is recommended to have a complete development environment, such as [ServBay](https://www.servbay.com), [Herd](https://herd.laravel.com/docs), or [Valet](https://laravel.com/docs/valet). However, Tempest can serve applications using PHP's built-in server as well:


```sh
{:hl-keyword:php:} tempest serve
```


### Scaffolding front-end assets

After having installed Tempest, you can optionally install a basic front-end scaffolding that includes [Vite](https://vite.dev/) and [Tailwind CSS](https://tailwindcss.com/). To do so, run the Vite installer and follow through the wizard:

```sh
{:hl-keyword:php:} tempest install vite
```

The assets created by this wizard, `main.entrypoint.ts` and `main.entrypoint.css`, are automatically discovered by Tempest. You can serve them using the [`{html}<x-vite-tags />`](../1-essentials/03-views#x-vite-tags) component in your templates.

You may then [run the front-end development server](../1-essentials/04-asset-bundling#running-the-development-server), which will serve your assets on-the-fly:

```bash
{:hl-keyword:npm:} run dev
```

## Project structure

Tempest won't impose any fixed file structure on you: the framework gives you the freedom to design your project the way you and your team want. Tempest can give you this freedom without any configuration overhead thanks to one of its core features called [discovery](../4-internals/02-discovery).

For example, here are two different project structures side by side:

```txt
.                                    .
└── src                              └── src
    ├── Authors                          ├── Controllers
    │   ├── Author.php                   │   ├── AuthorController.php
    │   ├── AuthorController.php         │   └── BookController.php
    │   └── authors.view.php             ├── Models
    ├── Books                            │   ├── Author.php
    │   ├── Book.php                     │   ├── Book.php
    │   ├── BookController.php           │   └── Chapter.php
    │   ├── Chapter.php                  ├── Services
    │   └── books.view.php               │   └── PublisherGateway.php
    ├── Publishers                       └── Views
    │   └── PublisherGateway.php             ├── authors.view.php
    └── Support                              ├── books.view.php
        └── x-base.view.php                  └── x-base.view.php      
```

From Tempest's perspective, your project structure doesn't make a difference.

### About Discovery

Discovery works by scanning your project code, and looking at each file and method individually to determine what that code does. In production environments, [Tempest will cache the discovery process](../4-internals/02-discovery#discovery-in-production), avoiding any performance overhead.

As an example, Tempest is able to determine which methods are controller methods based on their route attributes, such as `#[Get]` or `#[Post]`:

```php app/BlogPostController.php
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

Likewise, it is able to detect console commands based on the `#[ConsoleCommand]` attribute:

```php src/RssSyncCommand.php
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

Or event listeners:

```php app/MigrationListeners.php
use Tempest\Console\Console;
use Tempest\EventBus\EventHandler;

final readonly class MigrationListeners
{
    public function __construct(
        private Console $console,
    ) {}
    
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

Discovery is one of Tempest's most powerful features, if you want to better understand the framework, you could read this article to learn about [discovery in depth](/blog/discovery-explained). You can also continue reading the docs to learn about Tempest's essential components next.