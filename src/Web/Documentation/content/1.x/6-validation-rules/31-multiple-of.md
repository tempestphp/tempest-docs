---
title: MultipleOf
description: "Validate that a value is a multiple of a specific number."
---

## Overview

This rule validates whether a given integer value is a multiple of a specific number.

## Parameters

- `$divisor` (`int`): The number by which the value must be a multiple.

## Example

```php
use Tempest\Validation\Rules\MultipleOf;

final class Number
{
    #[MultipleOf(5)]
    public int $value;
}
```

## See also

- [Tempest\Validation\Rules\DivisibleBy](11-divisible-by.md)
