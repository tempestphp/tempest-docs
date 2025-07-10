---
title: AfterDate
description: "Validate that a date is after a specific date."
---

## Overview

This rule validates whether a given date value is after a specified date. You can also choose to include the specified date in the validation.

## Parameters

- `$date` (`DateTimeInterface|DateTimeImmutable|string`): The date to compare against. Defaults to `now`.
- `$inclusive` (`bool`): Whether to include the specified date in the validation. Defaults to `false`.

## Examples

### After a specific date

```php
use DateTimeImmutable;
use Tempest\Validation\Rules\AfterDate;
use Tempest\DateTime\DateTime;

final class Book
{
    #[AfterDate('2023-01-01')]
    public DateTime $fooDate;

    #[AfterDate(new DateTime('2023-01-01'))]
    public DateTime $barDate;

    #[AfterDate(new DateTimeImmutable('2023-01-01'))]
        public DateTime $bazDate;
}
```

### After a specific date (inclusive)

```php
use Tempest\Validation\Rules\AfterDate;

final class Book
{
    #[AfterDate('2023-01-01', inclusive: true)]
    public DateTime $fooDate;

    #[AfterDate(new DateTime('2023-01-01'), inclusive: true)]
    public DateTime $barDate;

    #[AfterDate(new DateTimeImmutable('2023-01-01'), inclusive: true)]
    public DateTime $bazDate;
}
```

## See also

- [Tempest\Validation\Rules\BeforeDate](06-before-date.md)
- [Tempest\Validation\Rules\BetweenDates](08-between-dates.md)
