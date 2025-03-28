---
title: Models
description: "Model classes represent the heart of our application logic. Tempest provides a powerful way of persisting model data."
keywords: ["experimental", "orm", "database"]
---

:::warning
The ORM is currently experimental and is not covered by our backwards compatibility promise. Important features such as query builder relationships are not polished nor documented.

We are currently discussing about taking a different approach to the ORM. [We'd like to hear your opinion](https://github.com/tempestphp/tempest-framework/issues/1074)!
:::

## Overview

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

## Model mapping

[Tempest's mapper](../2-tempest-in-depth/01-mapper) is able to map data from many different sources to a model. For instance, you can specify the path to a JSON file or object to create an instance of a model, and the other way around.

```php
use function Tempest\map;

$books = map($json)->collection()->to(Book::class);

$json = map($books)->toJson();
```

However, the most common use case is to persist models to databases, so Tempest comes with a set of tools specifically aimed at database models.

## Database persistence

Tempest comes with a bunch of query builders that can work with any object and convert them into database queries. We'll start by exploring the {`Tempest\Database\IsDatabaseModel`} trait first though: this is a higher-level trait that can be used to any object and adds a bunch of convenient shorthand methods to those objects to interact with the database. Note that using `IsDatabaseModel` is optional, we'll explore other ways of interacting with the database later in this chapter.

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

Thanks to the `IsDatabaseModel` trait, you can directly interact with the database via the model class:

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

Tempest will infer a table name for your models based on the model's class name. You can override this name by using the `TableName` attribute:

```php
use Tempest\Database\TableName;

#[TableName('my_books')]
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

:::warning
All `migrate:up` and `migrate:fresh` commands validate migration files to ensure their integrity by default.

If you don't want to validate migration files, you can use the `--no-validate` argument.
:::

The `migrate:validate` command checks the integrity of migration files by comparing their current hash with the stored hash in the database. If a migration file has been tampered with, the command will report it as a validation failure.

```sh
{:hl-comment:# Validate migration files:}
./tempest migrate:validate
```

If any migration fails validation, it will be reported with an error message specifying the issue.

:::info
Only the actual SQL commands (minified and stripped of comments) are hashed during validation. This means that code-style changes, such as indentation or formatting, and comments will not impact the validation process.
:::

### Rehashing Migrations

The `migrate:rehash` command bypasses integrity checks to update stored migration hashes in the database.

:::warning
This operation can mask serious issues like tampered migration files or schema inconsistencies.

Only use this command when absolutely necessary and when you're confident that your migration files are correct and consistent across environments.
:::

```sh
./tempest migrate:rehash
```

## Database configuration

By default, Tempest uses a SQLite database stored in the `vendor/.tempest` directory. Changing databases is done by providing a {`Tempest\Database\Config\DatabaseConfig`} [configuration object](./06-configuration).

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
