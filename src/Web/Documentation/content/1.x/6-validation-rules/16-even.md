---
title: Even
description: "Validate if the value is an even number"
---

## Overview

This rule validates if the value is an even numer.

## Example

```php
use Tempest\Validation\Rules\Even;

final class Book
{
    #[Even()]
    public string $number;
}
```

## See also

- [Tempest\Validation\Rules\Odd](36-odd.md)
