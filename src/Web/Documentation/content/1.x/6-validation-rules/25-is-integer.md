---
title: IsInteger
description: "Validate that a value is an integer."
---

## Overview

This rule validates whether a given value is an integer.

## Parameters

- `$orNull` (`bool`): Whether to allow `null` values. Defaults to `false`.

## Examples

### Default

```php
use Tempest\Validation\Rules\IsInteger;

final class Product
{
    #[IsInteger()]
    public int $quantity;
}
```

### Allow null values

```php
use Tempest\Validation\Rules\IsInteger;

final class Product
{
    #[IsInteger(orNull: true)]
    public ?int $quantity;
}
```

## See also

- [Tempest\Validation\Rules\IsFloat](24-is-float.md)
- [Tempest\Validation\Rules\Numeric](35-numeric.md)
