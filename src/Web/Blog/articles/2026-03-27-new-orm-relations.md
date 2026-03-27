---
title: New ORM relations
description: Tempest's ORM now supports HasOneThrough, HasManyThrough, and BelongsToMany relations
tag: release
author: brent
---

Thanks to the work of [Layla Tichi](https://github.com/tempestphp/tempest-framework/issues?q=sort%3Aupdated-desc+is%3Apr+author%3Alaylatichy), Tempest's ORM has gotten a significant upgrade. 

First, there's the {b`#[Tempest\Database\HasOneThrough]`} attribute. It defines a one-to-one relationship that traverses through an intermediate model. This lets you access a distant relation directly, resolved in a single SQL query with two JOINs.

```php
use Tempest\Database\HasOne;
use Tempest\Database\HasOneThrough;

final class Author
{
    #[HasOne]
    public ?Profile $profile = null;

    #[HasOneThrough(Profile::class)]
    public ?Address $address = null;
}
```

Here's what the join statement looks like:

```sql
LEFT JOIN profiles ON profiles.author_id = authors.id
LEFT JOIN addresses ON addresses.profile_id = profiles.id
```

Next is the {b`#[Tempest\Database\HasManyThrough]`} attribute. This one defines a one-to-many relationship that traverses through an intermediate model. This lets you access a collection of distant relations directly, resolved in a single SQL query with two JOINs.

```php
use Tempest\Database\HasManyThrough;

final class Author
{
    /** @var \App\Payment\Payment[] */
    #[HasManyThrough(Contract::class)]
    public array $payments = [];
}
```

Here's what that join statement looks like:

```sql
LEFT JOIN contracts ON contracts.author_id = authors.id
LEFT JOIN payments ON payments.contract_id = contracts.id
```

Finally, the {b`#[Tempest\Database\BelongsToMany]`} attribute defines a many-to-many relationship using a pivot table. Both sides of the relationship can declare the attribute.

```php
use Tempest\Database\BelongsToMany;

final class Author
{
    /** @var \App\Tag\Tag[] */
    #[BelongsToMany]
    public array $tags = [];
}

final class Tag
{
    /** @var \App\Author\Author[] */
    #[BelongsToMany]
    public array $authors = [];
}
```

The pivot table name is inferred alphabetically from both model table names (e.g., `authors` + `tags` = `authors_tags`). This generates SQL like:

```sql
LEFT JOIN authors_tags ON authors_tags.author_id = authors.id
LEFT JOIN tags ON tags.id = authors_tags.tag_id
```

Of course, there's a lot more you can do with these attributes to make them work exactly as you want. You can [find out all the details in the docs](/3.x/essentials/database#has-one-through).