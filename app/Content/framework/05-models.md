---
title: Models
---

In contrast to many popular ORMs, Tempest models aren't required to be tied to the database. A model's persisted data can be loaded from any kind of data source: an external API, JSON, Redis, XML, … In essence, a model is nothing more than a class with public typed properties and methods. Tempest will use a model class' type information to determine how data should be mapped between objects.

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

## Database config

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

## Migrations

Migrations are used to manage database tables that hold persisted model data. Migrations are discovered, so you can create them wherever you like, as long as they implement the `{php}Migration` interface:

```php
use Tempest\Database\QueryStatement;

final readonly class CreateBookTable implements Migration
{
    public function getName(): string
    {
        return '2024-08-12_create_book_table';
    }

    public function up(): QueryStatement|null
    {
        return CreateTableStatement::forModel(Book::class)
            ->primary()
            ->text('title')
            ->datetime('createdAt')
            ->datetime('publishedAt', nullable: true)
            ->integer('author_id', unsigned: true)
            ->belongsTo('Book.author_id', 'Author.id');
    }

    public function down(): QueryStatement|null
    {
        return DropTableStatement::forModel(Book::class);
    }
}
```

As an alternative, you can write plain SQL files which will be discovered as migrations as well. The file name will be used as the migration's name. Note that you can have multiple queries in one sql file, each of them will be run as a separate migrations:

```sql
-- app/Migrations/2024-08-16_create_publisher_table.sql

CREATE TABLE Publisher
(
    `id`   INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT NOT NULL
);

-- You can add several queries into one sql file if you want to
```

Please take note of some naming conventions:

- Model tables use the **model's short classname**, use the `::forModel()` method for convenience
- Fields map 1 to 1 to a **model's property names**. It's up to you to use camelCase or snake_case
- Relation fields are always suffixed with `_id`. In this case, `author_id` will map to the `Book::$author` property

You can run migrations via the Tempest console:

```
./tempest migrate:up
./tempest migrate:down
./tempest migrate:fresh {:hl-comment:# Drop all tables and rerun migrate:up:} 
```

## Database persistence

Any class implementing `DatabaseModel` provides a range of methods to make interaction between the model and the database easier. Let's take a look at this interface:

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

## Model query builder

Important to note is the `DatbaseModel::query()` method, which allows you to create more complex queries for model classes. It's important to note that Tempest deliberately takes a simplistic approach to its model query builder. If you want to build real complex queries, you should write them directly in SQL, and map them to model classes like so:

```php
$books = map(new Query("
    SELECT * 
    FROM Book
    LEFT JOIN …
    HAVING … 
"))->collection()->to(Book::class);
```

For simpler queries, you can use the query builder API.

```php
$books = Book::query()
    ->with('author.publisher')
    ->where('createdAt < :olderThan', olderThan: $olderThan)
    ->orderBy('createdAt DESC')
    ->limit(5)
    ->all();
```

## Model relations

TODO: write docs