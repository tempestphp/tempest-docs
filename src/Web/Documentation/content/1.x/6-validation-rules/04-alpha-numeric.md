---
title: AlphaNumeric
description: "Validate that a value contains only alphanumeric characters."
---

## Overview

This rule validates whether a given string value contains only alphanumeric characters (A-Z, a-z, 0-9).

## Example

```php
use Tempest\Validation\Rules\AlphaNumeric;

final class User
{
    #[AlphaNumeric()]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\Alpha](03-alpha.md)
