---
title: Odd
description: "Validate if the value is an odd number"
---

## Overview

This rule validates if the value is an odd numer.

## Example

```php
use Tempest\Validation\Rules\Odd;

final class Book
{
    #[Odd()]
    public string $number;
}
```

## See also

- [Tempest\Validation\Rules\Even](16-even.md)
