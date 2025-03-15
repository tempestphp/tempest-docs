---
title: Framework bootstrap
category: internals
---

Here's a short summary of what booting Tempest looks like.

- Tempest boots using the `\Tempest\Framework\Application\Kernel` class, the first step is to build the container
- Then there are **bootstrap** classes, each with an individual task. You find them in `\Tempest\Framework\Application\Bootstraps`
- Two bootstraps (`DiscoveryLocationBootstrap` and `DiscoveryBootstrap`) are tasked with setting up and executing **discovery**, which is the mechanism that will scan the whole codebase, and automatically register classes, you can read about it in the [next chapter](/docs/internals/02-discovery).
- The other bootstrap (`ConfigBootstrap`) is used for loading **config**. For now, loading config happens independent of discovery, but we plan on refactoring that.
- When bootstrapping is completed, the kernel return a fully configured container, which can be used to boot an application.

## Applications

Tempest provides two applications: `ConsoleApplication` and `HttpApplication`. Their name already suggests the difference between the two. Here's how to boot an application:

- As soon you've got a configured container object from the kernel, you can resolve a specific application from it: `$application = $container->get(ConsoleApplication::class)`.
- You can provide additional application-specific configuration and setup if needed.
- Finally, you can call `$application->run()` to run it.

Because kernel bootstrap and application boot are two low-level framework concerns, Tempest offers a class that handles all these steps for you behind the scenes: `\Tempest\Framework\Tempest`.

This is the only line framework users should write to actually boot and run Tempest:

```php
// For console applications:
Tempest::boot(getcwd())->console()->run();

// For HTTP applications:
Tempest::boot(__DIR__ . '/../')->http()->run();
```

## Loading config

- `ConfigBootstrap` will scan all registered **discovery locations**, and search for a folder called `{txt}Config/` within each location. All files with `.php` will be registered as config files
- Discovery locations are determined based on composer. For example, your project root namespace is a discovery location (most likely called `app` or `src`). Another example is the Tempest vendor code itself, living in `vendor/tempest`. For now, it's sufficient to know that Tempest will detect all project code for you based on composer, and scan everything that's contains tempest code.
- Whenever config files are detected, they are registered as singletons in the container. That means that every config file is available to be injected in any class.

A config file would look something like this:

```php
// app/Config/database.php

<?php

use Tempest\Database\DatabaseConfig;
use Tempest\Database\Drivers\SQLiteDriver;

return new DatabaseConfig(
    driver: new SQLiteDriver(
        path: __DIR__ . '/../database.sqlite',
    ),
);
```

Note that project-level config files are **optional**, Tempest loads default implementations if there's no user-specific config file. These default config files are located in `src/Tempest/Framework/Config/`. Whenever you create a new config class on the framework level, you should also add a corresponding config file for it in this location.
