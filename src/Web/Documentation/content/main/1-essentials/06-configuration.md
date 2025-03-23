---
title: Configuration
---

## Configuration

As mentioned, configuration is represented by objects in Tempest. Tempest provides many configuration classes for you, although the framework is designed to use them as little as possible. Whenever you need fine-grained control over part of the framework's config, you can overwrite Tempest's default config by creating one or more `*.config.php` files, anywhere in your project. Each `*.config.php` file should return one config object.

```php
// app/database.config.php

use Tempest\Database\DatabaseConfig;
use Tempest\Database\Connections\MySqlConnection;
use function Tempest\env;

return new DatabaseConfig(
    connection: new MySqlConnection(
        host: env('DB_HOST'),
        port: env('DB_PORT'),
        username: env('DB_USERNAME'),
        password: env('DB_PASSWORD'),
        database: env('DB_DATABASE'),
    ),
);
```

Project-level configuration files will be discovered automatically, and will overwrite Tempest's default config. In this example, the default `DatabaseConfig` object will be overwritten by your custom one, using MySQL instead of SQLite, and retrieving its credentials from environment variables.

### Config Cache

Config files are cached by Tempest, you can read more about caching in the [dedicated chapter](/docs/framework/caching). You can enable or disable config cache with the `{txt}{:hl-property:CONFIG_CACHE:}` environment variable.

```env
{:hl-comment:# .env:}

{:hl-property:CONFIG_CACHE:}={:hl-keyword:true:}
```
