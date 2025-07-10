---
title: NotIn
description: "Validate that a value is not one of a given set of values."
---

## Overview

This rule validates whether a given value is not present in a given array of values.

## Parameters

- `$values` (`array<string|int>`): The array of values to check against.

## Example

```php
use Tempest\Validation\Rules\NotIn;

final class User
{
    #[NotIn(['admin', 'root'])]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\In](18-in.md)
