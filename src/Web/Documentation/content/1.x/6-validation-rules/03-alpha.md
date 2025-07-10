---
title: Alpha
description: "Validate that a value contains only alphabetic characters."
---

## Overview

This rule validates whether a given string value contains only alphabetic characters (A-Z, a-z).

## Example

```php
use Tempest\Validation\Rules\Alpha;

final class User
{
    #[Alpha()]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\AlphaNumeric](04-alpha-numeric.md)
