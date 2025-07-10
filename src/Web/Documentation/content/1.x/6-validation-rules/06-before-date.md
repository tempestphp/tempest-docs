---
title: BeforeDate
description: "Validate that a date is before a specific date."
---

## Overview

This rule validates whether a given date value is before a specified date. You can also choose to include the specified date in the validation.

## Parameters

- `$date` (`DateTimeInterface|DateTimeImmutable|string`): The date to compare against. Defaults to `now`.
- `$inclusive` (`bool`): Whether to include the specified date in the validation. Defaults to `false`.

## Examples

### Before a specific date

```php
use Tempest\Validation\Rules\BeforeDate;
use Tempest\DateTime\DateTime;

final class Book
{
    #[BeforeDate('2023-01-01')]
    public DateTime $fooDate;

    #[BeforeDate(new DateTime('2023-01-01'))]
    public DateTime $barDate;

    #[BeforeDate(new DateTimeImmutable('2023-01-01'))]
    public DateTime $bazDate;
}
```

### Before a specific date (inclusive)

```php
use Tempest\Validation\Rules\BeforeDate;

final class Book
{
    #[BeforeDate('2023-01-01', inclusive: true)]
    public DateTime $fooDate;

    #[BeforeDate(new DateTime('2023-01-01'), inclusive: true)]
    public DateTime $barDate;

    #[BeforeDate(new DateTimeImmutable('2023-01-01'), inclusive: true)]
    public DateTime $bazDate;
}
```

## See also

- [Tempest\Validation\Rules\AfterDate](02-after-date.md)
- [Tempest\Validation\Rules\BetweenDates](08-between-dates.md)
