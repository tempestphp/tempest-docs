---
title: IsString
description: "Validate that a value is a string."
---

## Overview

This rule validates whether a given value is a string or a Stringable object.

## Parameters

- `$orNull` (`bool`): Whether to allow `null` values. Defaults to `false`.

## Examples

### Default

```php
use Tempest\Validation\Rules\IsString;

final class User
{
    #[IsString()]
    public string $name;
}
```

### Allow null values

```php
use Tempest\Validation\Rules\IsString;

final class User
{
    #[IsString(orNull: true)]
    public ?string $name;
}
```
