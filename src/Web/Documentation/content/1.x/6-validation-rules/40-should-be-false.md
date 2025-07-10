---
title: ShouldBeFalse
description: "Validate that a value is false."
---

## Overview

This rule validates whether a given value is `false`, `'false'`, `0`, or `'0'`.

## Example

```php
use Tempest\Validation\Rules\ShouldBeFalse;

final class User
{
    #[ShouldBeFalse()]
    public bool $isActive;
}
```

## See also

- [Tempest\Validation\Rules\ShouldBeTrue](41-should-be-true.md)
- [Tempest\Validation\Rules\IsBoolean](22-is-boolean.md)
