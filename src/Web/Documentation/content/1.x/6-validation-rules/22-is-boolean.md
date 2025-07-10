---
title: IsBoolean
description: "Validate that a value is a boolean."
---

## Overview

This rule validates whether a given value can be interpreted as a boolean. The values `true`, `'true'`, `1`, `'1'`, `false`, `'false'`, `0`, and `'0'` are all considered valid booleans.

## Parameters

- `$orNull` (`bool`): Whether to allow `null` values. Defaults to `false`.

## Example

```php
use Tempest\Validation\Rules\IsBoolean;

final class Settings
{
    #[IsBoolean()]
    public bool $darkMode;
}
```

### Allow null values

```php
use Tempest\Validation\Rules\IsBoolean;

final class Settings
{
    #[IsBoolean(orNull: true)]
    public ?bool $darkMode;
}
```

## See also

- [Tempest\Validation\Rules\ShouldBeTrue](41-should-be-true.md)
- [Tempest\Validation\Rules\ShouldBeFalse](40-should-be-false.md)
