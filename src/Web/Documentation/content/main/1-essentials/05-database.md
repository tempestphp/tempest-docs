---
title: Database
description: "Database interactions are the heart of applications. Tempest provides a minimalistic query builder, but its main strength is in its integrations with the mapper."
keywords: ["Experimental", "ORM"]
---

:::warning
The database layer of Tempest is currently experimental and is not covered by our backwards compatibility promise. Important features such as query builder relationships are not polished nor documented.

We are currently discussing about taking a different approach to the ORM. [We'd like to hear your opinion](https://github.com/tempestphp/tempest-framework/issues/1074)!
:::

## Overview

Because database persistence is a pretty common use case, Tempest provides an implementation for models that should interact with the database specifically.

Model classes can implement the {`Tempest\Database\DatabaseModel`} interface and include its default implementation with the {`Tempest\Database\IsDatabaseModel`} trait.

```php app/Book.php
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;
use Tempest\Validation\Rules\Length;
use App\Author;

final class Book implements DatabaseModel
{
    use IsDatabaseModel;

    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Chapter[] */
    public array $chapters = [];
}
```

## Changing databases

By default, Tempest uses a SQLite database stored in the `vendor/.tempest` directory. Changing databases is done by providing a {`Tempest\Database\Config\DatabaseConfig`} [configuration object](./06-configuration) to the framework.

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

## Migrations

A migration is a file instructing the framework how to apply a change to the database schema. Tempest uses migrations to create and update databases accross different environments.

### Writing migrations

Thanks to [discovery](../4-internals/02-discovery), `.sql` files and classes implementing the {`Tempest\Database\DatabaseMigration`} interface are automatically registered as migrations, which means they can be stored anywhere.

```php app/CreateBookTable.php
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final readonly class CreateBookTable implements DatabaseMigration
{
    public string $name {
        get {
            return '2024-08-12_create_book_table';
        }
    }

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

:::info
The file name of `{txt}.sql` migrations and the `{txt}{:hl-type:$name:}` property of `DatabaseMigration` classes are used to determine the order in which they are applied. A good practice is to use their creation date as a prefix.
:::

### Applying migrations

A few [console commands](../3-console/02-building-console-commands) are provided to work with migrations. They are used to apply, rollback, or erase and re-apply them. When deploying your application to production, you should use the `php tempest migrate:up` to apply the latest migrations.

```sh
{:hl-comment:# Applies migrations that have not been run in the current environment:}
./tempest migrate:up

{:hl-comment:# Rolls back every migration:}
./tempest migrate:down

{:hl-comment:# Drops all tables and rerun migrate:up:}
./tempest migrate:fresh
```

## The query builder

Classes implementing the {`Tempest\Database\DatabaseModel`} interface have access to a `query()` method, which returns a new instance of {`Tempest\Database\Builder\ModelQueryBuilder`}.

This offers a way of creating simply queries using a fluent builder interface.

```php
$books = Book::query()
    ->with('author.publisher')
    ->where('createdat < :olderThan', olderThan: $olderThan)
    ->orderBy('created_at DESC')
    ->limit(5)
    ->all();
```

For queries that are more complex, you may use a raw query using the {`Tempest\Database\Query`} class. When used in conjunction with the [mapper](../2-tempest-in-depth/01-mapper), you may obtain hydrated instances of your model.

```php
use Tempest\Database\Query;
use function Tempest\map;

$books = map(new Query(<<<SQL
    SELECT *
    FROM books
    LEFT JOIN …
    HAVING …
SQL))->collection()->to(Book::class);
```

## Virtual properties

By default, all public properties are considered to be part of the model's query fields. In order to exclude a field from the database mapper, you may use the `Tempest\Database\Virtual` attribute.

```php
use Tempest\Database\Virtual;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

class Book implements DatabaseModel
{
    use IsDatabaseModel;

    // …

    public DateTimeImmutable $publishedAt;

    #[Virtual]
    public DateTimeImmutable $saleExpiresAt {
        get => $this->publishedAt->add(new DateInterval('P5D'));
    }
}
```
