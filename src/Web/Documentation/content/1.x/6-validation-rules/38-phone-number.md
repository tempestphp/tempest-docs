---
title: PhoneNumber
description: "Validate that a value is a valid phone number."
---

## Overview

This rule validates whether a given string value is a valid phone number. You can optionally provide a default region.

## Parameters

- `$defaultRegion` (`?string`): The default region code (e.g., `NL`, `BE`). Defaults to `null`.

## Examples

### Default

```php
use Tempest\Validation\Rules\PhoneNumber;

final class User
{
    #[PhoneNumber()]
    public string $phoneNumber;
}
```

### With default region

```php
use Tempest\Validation\Rules\PhoneNumber;

final class User
{
    #[PhoneNumber(defaultRegion: 'NL')]
    public string $phoneNumber;
}
```
