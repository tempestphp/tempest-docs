---
title: Between
description: "Validate that a value is between a minimum and maximum value."
---

## Overview

This rule validates whether a given numeric value is between a specified minimum and maximum value (inclusive).

## Parameters

- `$min` (`int`): The minimum allowed value.
- `$max` (`int`): The maximum allowed value.

## Example

```php
use Tempest\Validation\Rules\Between;

final class Product
{
    #[Between(min: 1, max: 100)]
    public int $stock;
}
```

## See also

- [Tempest\Validation\Rules\Count](09-count.md)
- [Tempest\Validation\Rules\Length](28-length.md)
