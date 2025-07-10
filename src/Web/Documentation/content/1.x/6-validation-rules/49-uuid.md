---
title: Uuid
description: "Validate that a value is a valid UUID."
---

## Overview

This rule validates whether a given string value is a valid UUID.

## Example

```php
use Tempest\Validation\Rules\Uuid;

final class Post
{
    #[Uuid()]
    public string $id;
}
```

## See also

- [Tempest\Validation\Rules\Ulid](46-ulid.md)
