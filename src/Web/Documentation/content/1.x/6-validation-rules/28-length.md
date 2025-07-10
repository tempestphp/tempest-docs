---
title: Length
description: "Validate the length of a string."
---

## Overview

This rule validates the length of a string. You can specify a minimum, a maximum, or both.

## Parameters

- `$min` (`?int`): The minimum allowed length. Defaults to `null`.
- `$max` (`?int`): The maximum allowed length. Defaults to `null`.

## Examples

### Minimum length

```php
use Tempest\Validation\Rules\Length;

final class User
{
    #[Length(min: 3)]
    public string $username;
}
```

### Maximum length

```php
use Tempest\Validation\Rules\Length;

final class User
{
    #[Length(max: 32)]
    public string $username;
}
```

### Between a minimum and maximum length

```php
use Tempest\Validation\Rules\Length;

final class User
{
    #[Length(min: 3, max: 32)]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\Between](07-between.md)
- [Tempest\Validation\Rules\Count](09-count.md)
