---
title: IsFloat
description: "Validate that a value is a float."
---

## Overview

This rule validates whether a given value is a float.

## Parameters

- `$orNull` (`bool`): Whether to allow `null` values. Defaults to `false`.

## Examples

### Default

```php
use Tempest\Validation\Rules\IsFloat;

final class Product
{
    #[IsFloat()]
    public float $price;
}
```

### Allow null values

```php
use Tempest\Validation\Rules\IsFloat;

final class Product
{
    #[IsFloat(orNull: true)]
    public ?float $price;
}
```

## See also

- [Tempest\Validation\Rules\IsInteger](25-is-integer.md)
- [Tempest\Validation\Rules\Numeric](35-numeric.md)
