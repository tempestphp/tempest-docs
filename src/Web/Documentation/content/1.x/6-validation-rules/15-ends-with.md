---
title: EndsWith
description: "Validate that a value ends with a specific string."
---

## Overview

This rule validates whether a given string value ends with a specific string.

## Parameters

- `$needle` (`string`): The string that the value must end with.

## Example

```php
use Tempest\Validation\Rules\EndsWith;

final class File
{
    #[EndsWith('.css')]
    public string $filename;
}
```

## See also

- [Tempest\Validation\Rules\DoesNotEndWith](12-does-not-end-with.md)
- [Tempest\Validation\Rules\StartsWith](42-starts-with.md)
