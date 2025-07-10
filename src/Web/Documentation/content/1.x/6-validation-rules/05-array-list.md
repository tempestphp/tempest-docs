---
title: ArrayList
description: "Validate that a value is an array list."
---

## Overview

This rule validates whether a given value is an array list (a non-associative array).

## Example

```php
use Tempest\Validation\Rules\ArrayList;

final class Post
{
    #[ArrayList]
    public array $tags;
}
```
