---
title: Uppercase
description: "Validate that a string is uppercase."
---

## Overview

This rule validates whether a given string value is entirely uppercase.

## Example

```php
use Tempest\Validation\Rules\Uppercase;

final class User
{
    #[Uppercase()]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\Lowercase](29-lowercase.md)
