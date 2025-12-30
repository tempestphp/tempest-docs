```php app/sqlite.config.php
return new SQLiteConfig(
    path: env('DB_PATH', __DIR__ . '/../database.sqlite'),
);
```
