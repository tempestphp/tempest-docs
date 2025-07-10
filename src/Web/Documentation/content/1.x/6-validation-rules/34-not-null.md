---
title: NotNull
description: "Validate that a value is not null."
---

## Overview

This rule validates that a value is not `null`.

## Example

```php
use Tempest\Validation\Rules\NotNull;

final class User
{
    #[NotNull()]
    public string $name;
}
```
