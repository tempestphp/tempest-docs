---
title: Console
---

Tempest's console component can be used both within the framework, and as a standalone package. Console commands are discovered automatically by Tempest, and the framework ships with a console application in your project's root. You can call it like so:

```console
./tempest

<h1>Tempest</h1>

<h2>General</h2>
 <em>install</em> [--force=false] - Interactively install Tempest in your project
 <em>tail</em> [--project=null] [--server=null] [--debug=null] - Tail multiple logs
 <em>routes</em> - List all registered routes
 <em>serve</em> [host='localhost:8000'] [publicDir='public/'] - Start a PHP development server

<comment>…</comment>
```

And you can create console commands like so:

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    #[ConsoleCommand]
    public function all(): void
    {
        // …
    }

    #[ConsoleCommand]
    public function info(string $name): void
    {
        // …
    }
}
```

You can read all about how to use the Tempest console and how to create your own console commands in the [console docs](./02-building-console-commands).
