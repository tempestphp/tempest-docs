---
title: NotEmpty
description: "Validate that a string is not empty."
---

## Overview

This rule validates whether a given string value is not empty.

## Example

```php
use Tempest\Validation\Rules\NotEmpty;

final class Post
{
    #[NotEmpty()]
    public string $title;
}
```
