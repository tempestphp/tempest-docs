---
title: Getting Started
---

Tempest is a PHP MVC framework that gets out of your way. [**Give it a ⭐️ on GitHub**](https://github.com/tempestphp/tempest-framework)! Tempest's design philosophy is that developers should write as little framework-related code as possible, so that they can focus on application code instead.

## Installation

You can install Tempest in two ways: as a web app with a basic frontend bootstrap, or by requiring the framework as a package in any project you'd like.

### Tempest App

If you want to start a new Tempest project, you can use `tempest/app` as the starting point. Use `composer create-project` to start:

```txt
composer create-project tempest/app my-app
cd my-app
```

This project scaffold includes a basic frontend setup including tailwind:

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


## A basic Tempest project

Tempest won't impose any fixed file structure on you: one of the core principles of Tempest is that it will scan your project code for you, and it will automatically discover any files it needs to. For example: Tempest is able to differentiate between a controller method and a console command by looking at the code, instead of relying on naming conventions. This is what's called **discovery**, and it's one of Tempest's most powerful features. 

You can make a project that looks like this:

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

We'll cover controllers and console commands in depth in future chapters.