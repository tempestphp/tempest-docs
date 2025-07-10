---
title: DivisibleBy
description: "Validate that a value is divisible by a specific number."
---

## Overview

This rule validates whether a given numeric value is divisible by a specific number.

## Parameters

- `$divisor` (`int`): The number by which the value must be divisible.

## Example

```php
use Tempest\Validation\Rules\DivisibleBy;

final class Number
{
    #[DivisibleBy(2)]
    public int $value;
}
```

## See also

- [Tempest\Validation\Rules\MultipleOf](31-multiple-of.md)
