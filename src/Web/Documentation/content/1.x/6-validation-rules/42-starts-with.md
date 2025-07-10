---
title: StartsWith
description: "Validate that a value starts with a specific string."
---

## Overview

This rule validates whether a given string value starts with a specific string.

## Parameters

- `$needle` (`string`): The string that the value must start with.

## Example

```php
use Tempest\Validation\Rules\StartsWith;

final class User
{
    #[StartsWith('tempest-')]
    public string $username;
}
```

## See also

- [Tempest\Validation\Rules\DoesNotStartWith](13-does-not-start-with.md)
- [Tempest\Validation\Rules\EndsWith](15-ends-with.md)
