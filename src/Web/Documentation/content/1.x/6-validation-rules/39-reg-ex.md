---
title: RegEx
description: "Validate that a value matches a regular expression."
---

## Overview

This rule validates whether a given string value matches a regular expression.

## Parameters

- `$pattern` (`string`): The regular expression pattern to match against.

## Example

```php
use Tempest\Validation\Rules\RegEx;

final class User
{
    #[RegEx('/^[a-z]+$/')]
    public string $username;
}
```
