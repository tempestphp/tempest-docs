---
title: Numeric
description: "Validate that a value contains only numeric characters."
---

## Overview

This rule validates whether a given string value contains only numeric characters (0-9).

## Example

```php
use Tempest\Validation\Rules\Numeric;

final class User
{
    #[Numeric()]
    public string $accessCode$;
}
```

## See also

- [Tempest\Validation\Rules\IsInteger](25-is-integer.md)
- [Tempest\Validation\Rules\IsFloat](24-is-float.md)
