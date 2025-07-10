---
title: HexColor
description: "Validate that a value is a valid hexadecimal color."
---

## Overview

This rule validates whether a given string value is a valid hexadecimal color.

## Parameters

- `$orNull` (`bool`): Whether to allow `null` values. Defaults to `false`.

## Examples

### Default

```php
use Tempest\Validation\Rules\HexColor;

final class Design
{
    #[HexColor()]
    public string $primaryColor;
}
```

### Allow null values

```php
use Tempest\Validation\Rules\HexColor;

final class Design
{
    #[HexColor(orNull: true)]
    public ?string $primaryColor;
}
```
