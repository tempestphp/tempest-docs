---
title: Getting started
description: Tempest can be installed as a standalone PHP project, as well as a package within existing projects. The framework modules can also be installed individually, including in projects built on other frameworks.
---

## Installation

Tempest requires PHP [8.4+](https://www.php.net/downloads.php) and [Composer](https://getcomposer.org/) to be installed. Optionally, you may install either [Bun](https://bun.sh) or [Node](https://nodejs.org) if you chose to bundle front-end assets.

For a better experience, it is recommended to have a complete development environment, such as [ServBay](https://www.servbay.com), [Herd](https://herd.laravel.com/docs), or [Valet](https://laravel.com/docs/valet). However, Tempest can serve applications using PHP's built-in server just fine.

Once the prerequisites are installed, you can chose your installation method. Tempest can be a standalone application, or be added in an existing project—even one built on top of another framework.

## Creating a Tempest application

To get started with a new Tempest project, you may use {`tempest/app`} as the starting point. The `composer create-project` command will scaffold it for you:

```sh
composer create-project tempest/app my-app --stability alpha
cd my-app
```

If you have a dedicated development environment, you may then access your application by opening `https://my-app.test` in your browser. Otherwise, you may use PHP's built-in server:

```sh
php tempest serve
# PHP 8.3.3 Development Server (http://localhost:8000) started
```

### Scaffolding front-end assets

Optionally, you may install a basic front-end scaffolding that includes [Vite](https://vite.dev/) and [Tailwind CSS](https://tailwindcss.com/). To do so, run the Vite installer and follow through the wizard:

```sh
php tempest install vite --tailwind
```

<!-- TODO: docs -->
The assets created by this wizard, `main.entrypoint.ts` and `main.entrypoint.css`, are automatically discovered by Tempest. You can serve them using the `<x-vite-tags />` component in your templates.

You may then run the front-end development server, which will serve your assets on-the-fly:

```bash
npm run dev
```

## Tempest as a package

If you already have a project, you can opt to install {`tempest/framework`} as a standalone package. You could do this in any project; it could already contain code, or it could be an empty project.

```sh
composer require tempest/framework:1.0-alpha.5
```

Installing Tempest this way will give you access to the Tempest console, `./vendor/bin/tempest`. Optionally, you can choose to install Tempest's entry points in your project. To do so, you may run the framework installer:

```txt
./vendor/bin/tempest install framework
```

This installer will prompt you to install the following files into your project:

- `public/index.php` — the web application entry point
- `tempest` – the console application entry point
- `.env.example` – a clean example of a `.env` file
- `.env` – the real environment file for your local installation

You can choose which files you want to install, and you can always rerun the `install` command at a later point in time.

## Project structure

Tempest won't impose any file structure on you: one of its core features is that it will scan all project and package code for you, and will automatically discover any files the framework needs to know about.

For instance, Tempest is able to differentiate between a controller method and a console command by looking at the code, instead of relying on naming conventions or configuration files.

:::info
This concept is called [discovery](../3-internals/02-discovery), and is one of Tempest's most powerful features.
:::

### Examples

The following projects structures work the same way in Tempest, without requiring any specific configuration:

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

```txt
src
├── Blog
│   ├── BlogPostController.php
│   ├── RssSyncCommand.php
│   └── blog.view.php
└── Home
    ├── HomeController.php
    └── home.view.php
```

From Tempest's perspective, it's all the same.

## About Discovery

Discovery works by scanning your project code, and looking at each file and method individually to determine what that code does. For production application, Tempest will cache the discovery process, avoiding any performance overhead.

As an example, Tempest is able to determine which methods are controller methods based on their route attributes:

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

And likewise, it's able to detect console commands based on their console command attribute:

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

### Discovery in production

While discovery is a really powerful feature, it also comes with some performance considerations. In production environments, you want to make sure that the discovery workflow is cached. This is done by using the `DISCOVERY_CACHE` environment variable:

```env .env
{:hl-property:DISCOVERY_CACHE:}={:hl-keyword:true:}
```

The most important step is to generate that cache. This is done by running the `discovery:generate`, which should be part of your deployment pipeline. Make sure to run it before any other Tempest command.

```console
~ ./tempest discovery:generate
 ℹ  Clearing existing discovery cache…
 ✓  Discovery cached has been cleared
 ℹ  Generating new discovery cache… (cache strategy used: all)
 ✓  Cached 1119 items
```

### Discovery for local development

By default, the discovery cache is disabled in local development. Depending on your local setup, it is likely that you will not run into noticeable slowdowns. However, for larger projects, you might benefit from enabling a partial discovery cache:

```env .env
{:hl-property:DISCOVERY_CACHE:}={:hl-keyword:partial:}
```

This caching strategy will only cache discovery for vendor files. For this reason, it is recommended to run `discovery:generate` after every composer update:

```json
{:hl-comment:// …:}

"scripts": {
    "post-package-update": [
        "php tempest discovery:generate"
    ]
}
```

:::info
Note that, if you've created your project using {`tempest/app`}, you'll have the `post-package-update` script already included. You may read the [internal documentation about discovery](../3-internals/02-discovery) to learn more.
:::
