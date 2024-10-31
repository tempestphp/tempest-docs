---
title: Getting Started
---

`tempest/console` is a standalone package used to build console applications. [**Give it a ⭐️ on GitHub**](https://github.com/tempestphp/tempest-console)! 

You can install `tempest/console` like so:

```
composer require tempest/console:1.0-alpha.3
```

And run it like so:

```php
{:hl-comment:#!/usr/bin/env php:}
<?php

use Tempest\Console\ConsoleApplication;

require_once __DIR__ . '/vendor/autoload.php';

ConsoleApplication::boot()->run();
```

## Configuration

`tempest/console` uses on Tempest's discovery to find and register console commands. That means you don't have to register any commands manually, and any method within your codebase using the `{php}#[ConsoleCommand]` attribute will automatically be discovered by your console application.

```php
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final readonly class InteractiveCommand
{
    public function __construct(private Console $console) {}

    #[ConsoleCommand('hello:world')]
    public function __invoke(): void
    {
        $this->console->writeln('Hello World!');
    }
}
```

Tempest will discover all console commands within namespaces configured as composer PSR-4 autoload namespaces, as well as all third-party packages that require Tempest.

```json
"autoload": {
    "psr-4": {
        {:hl-hl:"App\\": "app/":}
    }
},
```

In case you need more fine-grained control over which directories to discover, you can provide a custom `{php}AppConfig` instance to the `{php}ConsoleApplication::boot()` method:

```php
use Tempest\AppConfig;

// …

$appConfig = new AppConfig(
    discoveryLocations: [
        {:hl-hl:new DiscoveryLocation('App\\', __DIR__ . '/app/'):}
    ],
);

ConsoleApplication::boot(appConfig: $appConfig)->run();
```