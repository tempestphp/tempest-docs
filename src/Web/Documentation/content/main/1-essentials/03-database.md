---
title: Database
description: "Learn how data persistence works within Tempest. We provide a minimal ORM with decoupled models, and a way to execute raw SQL queries."
keywords: ["experimental", "orm", "database", "sqlite", "postgresql", "pgsql", "mysql", "query", "sql", "connection", "models"]
---

:::warning
The ORM is currently experimental and is not covered by our backwards compatibility promise. Important features such as query builder relationships are not polished nor documented.
:::

## Overview

Tempest provides a database abstraction layer with support for PostgreSQL, MySQL and SQLite. Querying the database can be done using [raw SQL](#querying-the-database) or [our query builder](#non-object-models).

Additionally, [database models](#models) are completely decoupled from the ORM.

## Database configuration

By default, Tempest uses an SQLite database stored in the `vendor/.tempest` directory. Changing databases is done by providing a {`Tempest\Database\Config\DatabaseConfig`} [configuration object](./06-configuration).

Tempest ships with support for SQLite, PostgreSQL and MySQL. The corresponding configuration classes are `SQLiteConfig`, `PostgresConfig` and `MysqlConfig`, respectively.

For instance, you may configure Tempest to connect to a PostreSQL database by creating the following `database.config.php` file:

```php src/database.config.php
use Tempest\Database\Config\PostgresConfig;
use function Tempest\env;

return new PostgresConfig(
    host: env('DB_HOST'),
    port: env('DB_PORT'),
    username: env('DB_USERNAME'),
    password: env('DB_PASSWORD'),
    database: env('DB_DATABASE'),
);
```

## Querying the database

There are multiple way to query the database. You may use the `query()` function by passing it a table name or a model class name, or you may inject the {`Tempest\Database\Database`} interface in a service class.

```php
use Tempest\Database\Database;

use function Tempest\map;

final class UsersRepository
{
    public function __construct(
        private readonly Database $database,
    ) {}

    public function findById(int $id): User
    {
        $user = $this->database->fetchFirst(new Query(
            sql: <<<SQL
                SELECT id, name, email FROM users WHERE id = ?
            SQL,
            bindings: [$id],
        ));

        return map($user)->to(User::class);
    }
}
```

Alternatively, the `query()` function offers access to the query builder. Its first argument may be a table name, or a [model](#models) class.

```php
$user = query(User::class)
		->select('id', 'name', 'email')
		->whereField('id', $id)
		->first();
```

## Models

Any object with public, typed properties can represent a model—these objects don't have to implement anything, they may be plain-old PHP objects.

```php app/Book.php
use Tempest\Validation\Rules\Length;
use App\Author;

final class Book
{
    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Chapter[] */
    public array $chapters = [];
}
```

Such a model is not tied to the database. Tempest's [mapper](../2-features/01-mapper.md) is able to map data from many different sources to such a model. For instance, you can specify the path to a JSON file or object to create an instance of a model, and the other way around.

```php
use function Tempest\map;

$books = map($json)->collection()->to(Book::class); // from JSON source to Book collection
$json = map($books)->toJson(); // from Book collection to JSON
```

However, the most common use case is database persistence, so Tempest comes with a set of tools specifically aimed at database models.

### Database models

Sometimes, it's more convenient to have a model that is aware of the database, so you can easily create, update, and delete records.

Tempest provides a way to do this by using the {`Tempest\Database\IsDatabaseModel`} trait. As specified in the previous section, this trait is entirely optional.

```php
use Tempest\Database\IsDatabaseModel;
use Tempest\Validation\Rules\Length;
use App\Author;

final class Book
{
    use IsDatabaseModel;

    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Chapter[] */
    public array $chapters = [];
}
```

Thanks to the {b`Tempest\Database\IsDatabaseModel`} trait, you can directly interact with the database via the model class:

```php
$book = Book::create(
    title: 'Timeline Taxi',
    author: $author,
    chapters: [
        new Chapter(index: 1, contents: '…'),
        new Chapter(index: 2, contents: '…'),
        new Chapter(index: 3, contents: '…'),
    ],
);

$books = Book::select()
    ->where('publishedAt > ?', new DateTimeImmutable())
    ->orderBy('title DESC')
    ->limit(10)
    ->with('author')
    ->all();

$books[0]->chapters[2]->delete();
```

### Table naming

Tempest will infer a table name for your models based on the model's class name. You can override this name by using the `Table` attribute:

```php
use Tempest\Database\Table;

#[Table('my_books')]
final class Book
{
    // …
}
```

### Relation naming

Tempest will infer relation names based on property names. However, you can override these names with the `#[HasMany]`, `#[HasOne]`, and `#[BelongsTo]` attributes:

```php
use Tempest\Database\BelongsTo;
use Tempest\Database\HasMany;

final class Book
{
    #[BelongsTo(localPropertyName: 'author_uuid', inversePropertyName: 'uuid')]
    public ?Author $author = null;

    /** @var \App\Chapter[] */
    #[HasMany(inversePropertyName: 'book_uuid', localPropertyName: 'uuid')]
    public array $chapters = [];
}
```

### Virtual properties

By default, all public properties are considered to be part of the model's query fields. In order to exclude a field from the database mapper, you may use the `Tempest\Database\Virtual` attribute.

```php
use Tempest\Database\Virtual;

class Book
{
    // …

    public DateTimeImmutable $publishedAt;

    #[Virtual]
    public DateTimeImmutable $saleExpiresAt {
        get => $this->publishedAt->add(new DateInterval('P5D'));
    }
}
```

## Non-object models

While model objects are a convenient way of modelling data, you're not forced to use them. Instead, you can use the query builder directly to interact with the database:

```php
use function Tempest\Database\query;

$data = query('chapters')
    ->select('title', 'index')
    ->where('title = ?', 'Timeline Taxi')
    ->andWhere('index <> ?', '1')
    ->orderBy('index ASC')
    ->all();
```

```php
$data = query('chapters')
    ->count()
    ->where('title = ?', 'Timeline Taxi')
    ->andWhere('index <> ?', '1')
    ->execute();
```

```php
$data = query('chapters')
    ->count('title')
    ->execute();
```

```php
query('chapters')
    ->update(
        title: 'Chapter 01',
        index: 1,
    )
    ->where('id = ?', 10)
    ->execute();
```

```php
$chapters = [
    ['chapter' => 'Chapter 01', 'index' => 1],
    ['chapter' => 'Chapter 02', 'index' => 2],
    ['chapter' => 'Chapter 03', 'index' => 3],
];

$query = query('chapters')
    ->insert(...$chapters)
    ->execute();
```

```php
query('chapters')
    ->delete()
    ->where('index > ?', 10)
    ->andWhere('book_id = ?', 1)
    ->execute();
```

## Migrations

When you're persisting objects to the database, you'll need table to store its data in. A migration is a file instructing the framework how to manage that database schema. Tempest uses migrations to create and update databases across different environments.

### Writing migrations

Thanks to [discovery](../4-internals/02-discovery), `.sql` files and classes implementing the {`Tempest\Database\DatabaseMigration`} interface are automatically registered as migrations, which means they can be stored anywhere.

```php app/CreateBookTable.php
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final readonly class CreateBookTable implements DatabaseMigration
{
    public string $name = '2024-08-12_create_book_table';

    public function up(): QueryStatement|null
    {
        return new CreateTableStatement('books')
            ->primary()
            ->text('title')
            ->datetime('created_at')
            ->datetime('published_at', nullable: true)
            ->integer('author_id', unsigned: true)
            ->belongsTo('books.author_id', 'authors.id');
    }

    public function down(): QueryStatement|null
    {
        return new DropTableStatement('books');
    }
}
```

```sql app/2025-01-01_create_publisher_table.sql
CREATE TABLE Publisher
(
    `id`   INTEGER,
    `name` TEXT NOT NULL
);
```

:::info
The file name of `{txt}.sql` migrations and the `{txt}{:hl-type:$name:}` property of `DatabaseMigration` classes are used to determine the order in which they are applied. A good practice is to use their creation date as a prefix.
:::

Note that when using migration classes combined with query statements, Tempest will take care of the SQL dialect for you, there's support for MySQL, Postgresql, and SQLite. When using raw sql files, you'll have to pick a hard-coded SQL dialect, depending on your database requirements.

### Applying migrations

A few [console commands](../3-console/02-building-console-commands) are provided to work with migrations. They are used to apply, rollback, or erase and re-apply them. When deploying your application to production, you should use the `php tempest migrate:up` to apply the latest migrations.

```sh
{:hl-comment:# Applies migrations that have not been run in the current environment:}
./tempest migrate:up

{:hl-comment:# Rolls back every migration:}
./tempest migrate:down

{:hl-comment:# Drops all tables and rerun migrate:up:}
./tempest migrate:fresh

{:hl-comment:# Validates the integrity of migration files:}
./tempest migrate:validate
```

### Validating migrations

By default, an integrity check is done before applying database migrations with the `migrate:up` and `migrate:fresh` commands. This validation works by comparing the current migration hash with the one stored in the `migrations` table, if it was already applied in your environment.

If a migration file has been tampered with, the command will report it as a validation failure. Note that you may opt-out of this behavior by using the `--no-validate` argument.

Additionally, you may use the `migrate:validate` command to validate the integrity of migrations at any point, in any environment:

```sh
./tempest migrate:validate
```

:::tip
Only the actual SQL query of a migration, minified and stripped of comments, is hashed during validation. This means that code-style changes, such as indentation, formatting, and comments will not impact the validation process.
:::

### Rehashing migrations

You may use the `migrate:rehash` command to bypass migration integrity checks and update the hashes of migrations in the database.

```sh
./tempest migrate:rehash
```

:::warning
Note that deliberately bypassing migration integrity checks may result in a broken database state. Only use this command when absolutely necessary, if you are confident that your migration files are correct and consistent accross environments.
:::

## Using multiple databases

If you need to work with multiple databases, you may do so by creating a [tagged configuration](05-container.md1-container.md#dynamic-tags). It is the same as a normal configuration file, except the `tag` property must be specified. This tag will identify the connection in the container.

```php src/database.backup.config.php
return new PostgresConfig(
    tag: 'backup',
    host: env('BACKUP_DB_HOST'),
    port: env('BACKUP_DB_PORT'),
    username: env('BACKUP_DB_USERNAME'),
    password: env('BACKUP_DB_PASSWORD'),
    database: env('BACKUP_DB_DATABASE'),
);
```

To use a named connection, you may resolve it from the container [using its tag](05-container.md1-container.md#tagged-singletons):

```php src/BackupService.php
use Tempest\Database\Database;
use Tempest\Container\Tag;

final readonly class BackupService
{
    public function __construct(
        #[Tag('backup')]
        private Database $database,
    ) {}

    // …
}
```

## Configuring databases at runtime

There may be situations where you need to define a database connection at runtime, for instance when dealing with per-tenant databases. This may be done by registering a tagged database configuration in the container:

```php
$this->container->config(new SQliteConfig(
    tag: $tenant->name,
    path: $tenant->databasePath,
));
```

Resolving the {b`Tempest\Database\Database`} interface from the container with the same tag will use that configuration.

```php
$tenantDatabase = $this->container->get(Database::class, tag: $tenant->name);
```
