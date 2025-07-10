---
title: Count
description: "Validate the number of items in an array."
---

## Overview

This rule validates the number of items in an array. You can specify a minimum, a maximum, or both.

## Parameters

- `$min` (`?int`): The minimum number of items allowed. Defaults to `null`.
- `$max` (`?int`): The maximum number of items allowed. Defaults to `null`.

## Examples

### Minimum number of items

```php
use Tempest\Validation\Rules\Count;

final class Cart
{
    #[Count(min: 1)]
    public array $items;
}
```

### Maximum number of items

```php
use Tempest\Validation\Rules\Count;

final class Cart
{
    #[Count(max: 10)]
    public array $items;
}
```

### Between a minimum and maximum number of items

```php
use Tempest\Validation\Rules\Count;

final class Cart
{
    #[Count(min: 1, max: 10)]
    public array $items;
}
```

## See also

- [Tempest\Validation\Rules\Between](07-between.md)
- [Tempest\Validation\Rules\Length](28-length.md)
