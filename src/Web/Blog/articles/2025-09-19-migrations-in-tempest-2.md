---
title: No more down migrations
description: Database migrations have had a serious refactor in the newest Tempest release 
author: brent
tag: thoughts
---

With Tempest 2 comes a pretty significant change to how database migrations work. Luckily, the [upgrade process is automated](/blog/tempest-2). I thought it would be interesting to explain _why_ we made this change, though.

Previously, the `DatabaseMigration` interface looked like this:

```php
interface DatabaseMigration
{
    public string $name { get; }

    public function up(): ?QueryStatement;
    
    public function down(): ?QueryStatement;
}
```

Each migration had to implement both an `up()` and `down()` method. If your migration didn't need `up()` or `down()` functionality, you'd have to return `null`. This design was originally inspired by Laravel, and was one of the very early parts of Tempest that had never really changed. However, Freek recently wrote [a good blog post](https://freek.dev/2900-why-i-dont-use-down-migrations) on why he doesn't write down migrations anymore:

> At Spatie, we've embraced forward-only migrations for many years now.
>
> When something needs to be reversed, we will first think carefully about the appropriate solution for the particular situation we’re in. If necessary, we’ll handcraft a new migration that moves us forward rather than trying to reverse history.

Freek makes the point that "trying to reverse history with down migrations" is pretty tricky, especially if the migrations you're trying to roll back are already in production. I have to agree with him: up-migrations can already be tricky; trying to have consistent down-migrations as well is a whole new level of tricky-ness.

After reading Freek's blog post, I remembered: Tempest is a clean slate. Nothing is stopping us from using a different approach. That's why we removed the `DatabaseMigration` interface in Tempest 2. Instead there are now both the {b`Tempest\Database\MigratesUp`} and {b`Tempest\Database\MigratesDown`} interfaces. Yes, we kept the `MigratesDown` interface for now, and I'll elaborate a bit more on why later. First, let me show you what migrations now look like:

```php
use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;

final class CreateStoredEventTable implements MigratesUp
{
    public string $name = '2025-01-01-create_stored_events_table';

    public function up(): QueryStatement
    {
        return CreateTableStatement::forModel(StoredEvent::class)
            ->primary()
            ->text('uuid')
            ->text('eventClass')
            ->text('payload')
            ->datetime('createdAt');
    }
}
```

This is our recommended way of writing migrations: to only implement the {b`Tempest\Database\MigratesUp`} interface. Thanks to this refactor, we don't have to worry about nullable return statements on the interfaces as well, which I'd say is a nice bonus. Of course, you can still implement both interfaces in the same class if you really want to:

```php
use Tempest\Database\MigratesUp;
use Tempest\Database\MigratesDown;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateStoredEventTable implements MigratesUp, MigratedDown
{
    public string $name = '2025-01-01-stored_events_table';

    public function up(): QueryStatement
    {
        return new CreateTableStatement('stored_events')
            ->primary()
            ->text('uuid')
            ->text('eventClass')
            ->text('payload')
            ->datetime('createdAt');
    }

    public function down(): QueryStatement
    {
        return new DropTableStatement('stored_events');
    }
}
```

So why did we keep the `MigratesDown` interface? Some developers told me they like to use down migrations during development where they partially roll back the database while working on a feature. Personally, I prefer to always start from a fresh database and use [database seeders](/2.x/essentials/database#multiple-seeders) to bring it to a specific state. This way you'll always end up with the same database across developer machines, and can develop in a much more consistent way. You could, for example, make a seeder per feature you're working on, and so rollback the database to the right state during testing much more consistently:

```
./tempest migrate:fresh --seeder="Tests\Tempest\Fixtures\MailingSeeder"
{:hl-comment:# Or:}
./tempest migrate:fresh --seeder="Tests\Tempest\Fixtures\InvoiceSeeder"
```

Either way, we decided to keep `MigrateDown` in for now, and see the community's reaction to this new approach. We might get rid of down migrations altogether in the future, or we might keep them. Our recommended approach won't change, though: don't try to reverse the past, focus on moving forward. 