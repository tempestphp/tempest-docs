---
title: DoesNotEndWith
description: "Validate that a value does not end with a specific string."
---

## Overview

This rule validates whether a given string value does not end with a specific string.

## Parameters

- `$needle` (`string`): The string that the value must not end with.

## Example

```php
use Tempest\Validation\Rules\DoesNotEndWith;

final class File
{
    #[DoesNotEndWith('.tmp')]
    public string $filename;
}
```

## See also

- [Tempest\Validation\Rules\EndsWith](15-ends-with.md)
- [Tempest\Validation\Rules\DoesNotStartWith](13-does-not-start-with.md)
