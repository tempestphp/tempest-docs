---
title: Timestamp
description: "Validate that a value is a valid timestamp."
---

## Overview

This rule validates whether a given integer value is a valid Unix timestamp.

## Example

```php
use Tempest\Validation\Rules\Timestamp;

final class Event
{
    #[Timestamp()]
    public int $startTime;
}
```

## See also

- [Tempest\Validation\Rules\DateTimeFormat](10-date-time-format.md)
- [Tempest\Validation\Rules\Time](43-time.md)
