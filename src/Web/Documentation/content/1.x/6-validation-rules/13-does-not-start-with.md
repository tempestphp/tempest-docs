---
title: DoesNotStartWith
description: "Validate that a value does not start with a specific string."
---

## Overview

This rule validates whether a given string value does not start with a specific string.

## Parameters

- `$needle` (`string`): The string that the value must not start with.

## Example

```php
use Tempest\Validation\Rules\DoesNotStartWith;

final class User
{
    #[DoesNotStartWith('admin_')]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\StartsWith](42-starts-with.md)
- [Tempest\Validation\Rules\DoesNotEndWith](12-does-not-end-with.md)
