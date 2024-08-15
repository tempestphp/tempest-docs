---
title: Models
---

In contrast to many popular ORMs, Tempest models aren't required to be tied to the database. A model's persisted data can be loaded from any kind of data source: an external API, JSON, Redis, XML, … In essence, a model is nothing more than a class with public typed properties. Tempest will use this type information to determine how data should be mapped between objects.

In other words, a model can be as simple as this class:

```php
final class Book
{
    public function __construct(
        #[Length(min: 1, max: 120)]
        public string $title,
    
        public ?Author $author = null,
    
        /** @var \App\Modules\Books\Models\Chapter[] */
        public array $chapters = [],
    ) {}
}
```

Retrieving and persisting a model from a data source is done via Tempest's `{php}map()` function:

```php
map('path/to/books.json')->collection->to(Book::class);

map($book)->to(MapTo::JSON);
```

## Database models

Because database persistence is a pretty common use case, Tempest provides an implementation for models that should interact with the database specifically. Any model class can implement the `{php}DatabaseModel` interface, and use the `{php}IsDatabaseModel` trait like so:

```php
final class Book implements DatabaseModel
{
    use IsDatabaseModel;
    
    public function __construct(
        #[Length(min: 1, max: 120)]
        public string $title,
    
        public ?Author $author = null,
    
        /** @var \App\Modules\Books\Models\Chapter[] */
        public array $chapters = [],
    ) {}
}
```

### Database config

In order to connect to a database, you'll have to create a database config file:

```php
// app/Config/database.php

return new DatabaseConfig(
    driver: new SQLiteDriver(
        path: __DIR__ . '/../database.sqlite',
    ),
);
```

Tempest has three available database drivers: `{php}SQLiteDriver`, `{php}MySqlDriver`, and `{php}PostgreSqlDriver`. Note that you can use environment variables in config files like so:

```php
use function Tempest\env;

return new DatabaseConfig(
    driver: new MySqlDriver(
        host: env('DB_HOST'),
        port: env('DB_PORT'),
        username: env('DB_USERNAME'),
        password: env('DB_PASSWORD'),
        database: env('DB_DATABASE'),
    ),
);
```

### Migrations

TODO: write docs

### Database persistence

Let's take a look at the methods provided by the `{php}DatabaseModel` interface:

```php
interface Model
{
    // The model's table name. By default, it's the model's short classname 
    public static function table(): TableName;

    // Create a query builder for this model class
    public static function query(): ModelQueryBuilder;

    // Retrieve all instances from the database
    public static function all(): array;

    // Create a new model instance without saving it
    public static function new(mixed ...$params): self;

    // Create a new model instance and save it to the database
    public static function create(mixed ...$params): self;

    // Find a model based on some input data. If it doesn't exist, create it.
    // Next, update this model with some update data.
    public static function updateOrCreate(array $find, array $update): self;

    // Find a specific model, optionally loading relations as well
    public static function find(Id $id, array $relations = []): ?self;

    // Save a model instance to the database
    public function save(): self;

    // Get the model's id
    public function getId(): Id;
    
    // Set the model's id
    public function setId(Id $id): self;

    // Update a model instance and save it to the database
    public function update(mixed ...$params): self;

    // Load one or more relations for this model instance
    public function load(string ...$relations): self;
}
```

### Model query builder

TODO: write docs

### Model relations

TODO: write docs