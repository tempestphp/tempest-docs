---
title: Models
---

Models in Tempest are objects that represent the data and functionality of your application. Thanks to Tempest's `map` function, models can be as simple as an object, and their data could be coming from anywhere. 

```php
final class Book
{
    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Modules\Books\Models\Chapter[] */
    public array $chapters = [];
}
```



## Database models

Since most models are persisted within a database though, Tempest provides a trait called `BaseModel` (note: this trait will probably be renamed soon). Using this trait will make it so that your model classes can be persisted from and to a database.

Such model classes look like this:

```php
final class Book implements Model
{
    use BaseModel;

    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Modules\Books\Models\Chapter[] */
    public array $chapters = [];
}
```

Some key things to note about model definition:

- Tempest relies as much as possible on PHP's type system to handle its data mapping. For example: loading a book from the database with its author; Tempest knows which tables to query because of the relation to the `Author` model.
- You can add additional data validation rules using attributes. One example is the `#[Length]` attribute. There'll be a dedicated chapter about validation in the future.
- PHP unfortunately doesn't support typed collections, which means that Tempest relies on docblock definitions to define has many relations.
- The `Model` interface defines a set of methods that makes it easy to retrieve and persist models. It's important to note that the model class itself isn't concerned with SQL-specific query builders.

## Querying