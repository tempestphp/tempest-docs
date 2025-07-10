---
title: Time
description: "Validate that a value is a valid time."
---

## Overview

This rule validates whether a given string value is a valid time. You can specify whether the time should be in 24-hour format.

## Parameters

- `$twentyFourHour` (`bool`): Whether the time should be in 24-hour format. Defaults to `false`.

## Examples

### 12-hour format

```php
use Tempest\Validation\Rules\Time;

final class Event
{
    #[Time()]
    public string $startTime;
}
```

### 24-hour format

```php
use Tempest\Validation\Rules\Time;

final class Event
{
    #[Time(twentyFourHour: true)]
    public string $startTime;
}
```

## See also

- [Tempest\Validation\Rules\DateTimeFormat](10-date-time-format.md)
- [Tempest\Validation\Rules\Timestamp](44-timestamp.md)
