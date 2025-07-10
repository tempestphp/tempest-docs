---
title: ShouldBeTrue
description: "Validate that a value is true."
---

## Overview

This rule validates whether a given value is `true`, `'true'`, `1`, or `'1'`.

## Example

```php
use Tempest\Validation\Rules\ShouldBeTrue;

final class User
{
    #[ShouldBeTrue()]
    public bool $isActive;
}
```

## See also

- [Tempest\Validation\Rules\ShouldBeFalse](40-should-be-false.md)
- [Tempest\Validation\Rules\IsBoolean](22-is-boolean.md)
