---
title: Ulid
description: "Validate that a value is a valid ULID."
---

## Overview

This rule validates whether a given string value is a valid ULID.

## Example

```php
use Tempest\Validation\Rules\Ulid;

final class Post
{
    #[Ulid()]
    public string $id;
}
```

## See also

- [Tempest\Validation\Rules\Uuid](49-uuid.md)
