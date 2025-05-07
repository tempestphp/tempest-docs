---
title: Validation
---

Validation with Tempest is done by taking an array of raw input data, and validating whether that array of data is valid against a class. While validation and [data mapping](./01-mapper) often work together, the two are separate components and can also be used separately.

Here's an object that can be validated:

```php
final class Book
{
    public string $title;

    public string $description;

    public ?DateTimeImmutable $publishedAt = null;
}
```

As you can see, there are no explicit validation rules, that's because Tempest will first look at the object's type definitions and infer validation rules based on those: `$title` and `$description` are required since these aren't nullable properties, they should both be text; `$publishedAt` is optional, and it expects a valid date time string.

Validating an array of data against this `Book` object would look like this:

```php
use Tempest\Validation\Validator;

$validator = new Validator();

$failingRules = $validator->validateValuesForClass(Book::class,  [
    'title' => 'Timeline Taxi',
    'description' => 'My sci-fi novel',
    'publishedAt' => '2024-10-01',
]);
```

If validation fails, `$failingRules` will contain a list of fields and their respective failed rules.

## Validation Rules

As mentioned before, the validator makes use of rules to validate an array of data against a class. While the validator will infer some rules based on property types, there are a whole lot more that can be added via attributes:

```php
use Tempest\Validation\Rules\Length;
use Tempest\Validation\Rules\NotEmpty;
use Tempest\Validation\Rules\DateTimeFormat;

final class Book
{
    #[Length(min: 5, max: 50)]
    public string $title;

    #[NotEmpty]
    public string $description;

    #[DateTimeFormat('Y-m-d')]
    public ?DateTimeImmutable $publishedAt = null;
}
```

A list of all available validation rules can be found on [GitHub](https://github.com/tempestphp/tempest-framework/tree/main/src/Tempest/Validation/src/Rules).

## Skipping Validation

Some properties should never be validated. You can use the `#[SkipValidation]` attribute to exclude them from the validator:

```php
use Tempest\Validation\SkipValidation;

final class Book
{
    #[SkipValidation]
    public string $title;
}
```
