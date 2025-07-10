---
title: Lowercase
description: "Validate that a string is lowercase."
---

## Overview

This rule validates whether a given string value is entirely lowercase.

## Example

```php
use Tempest\Validation\Rules\Lowercase;

final class User
{
    #[Lowercase()]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\Uppercase](47-uppercase.md)
