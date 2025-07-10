---
title: DateTimeFormat
description: "Validate that a value is a date in a specific format."
---

## Overview

This rule validates whether a given string value is a date in a specific format. The format can be an ICU or legacy datetime format.

## Parameters

- `$format` (`string|FormatPattern`): An ICU or legacy datetime format.

## Examples

### Using a PHP date format

```php
use Tempest\Validation\Rules\DateTimeFormat;

final class Event
{
    #[DateTimeFormat('Y-m-d H:i:s')]
    public string $startTime;
}
```

### Using a FormatPattern

```php
use Tempest\Validation\Rules\DateTimeFormat;
use Tempest\DateTime\FormatPattern;

final class Event
{
    #[DateTimeFormat(FormatPattern::JAVASCRIPT)]
    public string $startTime;
}
```

## See also

- [Tempest\Validation\Rules\Time](43-time.md)
- [Tempest\Validation\Rules\Timestamp](44-timestamp.md)
