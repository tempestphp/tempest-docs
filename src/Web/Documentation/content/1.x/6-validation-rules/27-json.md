---
title: Json
description: "Validate that a value is a valid JSON string."
---

## Overview

This rule validates whether a given string value is a valid JSON string.

## Parameters

- `$depth` (`?int`): The maximum depth to which the JSON structure should be parsed. Defaults to `null`.
- `$flags` (`?int`): A bitmask of JSON constants. Defaults to `null`.

## Examples

### Default

```php
use Tempest\Validation\Rules\Json;

final class Data
{
    #[Json()]
    public string $payload;
}
```

### With depth

```php
use Tempest\Validation\Rules\Json;

final class Data
{
    #[Json(depth: 512)]
    public string $payload;
}
```

### With flags

```php
use Tempest\Validation\Rules\Json;
use const JSON_THROW_ON_ERROR;

final class Data
{
    #[Json(flags: JSON_THROW_ON_ERROR)]
    public string $payload;
}
```
