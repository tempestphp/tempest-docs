---
title: BetweenDates
description: "Validate that a date is between two other dates."
---

## Overview

This rule validates whether a given date value is between two other dates. You can specify whether the start and end dates should be included in the validation.

## Parameters

- `$first` (`DateTimeInterface|DateTimeImmutable|string`): The first date.
- `$second` (`DateTimeInterface|DateTimeImmutable|string`): The second date.
- `$inclusive` (`bool`): Whether to include the start and end dates in the validation. Defaults to `false`.

## Examples

### Between two dates (exclusive)

```php
use DateTimeImmutable;
use Tempest\Validation\Rules\BetweenDates;
use Tempest\DateTime\DateTime;
final class Event
{
    #[BetweenDates('2023-01-01', '2023-12-31')]
    public DateTime $fooDate;

    #[BetweenDates(new DateTime('2023-01-01'), new DateTime('2023-12-31'))]
    public DateTime $barDate;

    #[BetweenDates(new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-12-31'))]
    public DateTime $bazDate;
}
```

### Between two dates (inclusive)

```php
use Tempest\Validation\Rules\BetweenDates;

final class Event
{
    #[BetweenDates('2023-01-01', '2023-12-31', inclusive: true)]
    public DateTime $fooDate;

    #[BetweenDates(new DateTime('2023-01-01'), new DateTime('2023-12-31'), inclusive: true)]
    public DateTime $barDate;

    #[BetweenDates(new DateTimeImmutable('2023-01-01'), new DateTimeImmutable('2023-12-31'), inclusive: true)]
    public DateTime $bazDate;
}
```

## See also

- [Tempest\Validation\Rules\AfterDate](02-after-date.md)
- [Tempest\Validation\Rules\BeforeDate](06-before-date.md)
