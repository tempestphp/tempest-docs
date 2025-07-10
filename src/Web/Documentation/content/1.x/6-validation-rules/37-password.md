---
title: Password
description: "Validate that a string is a valid password."
---

## Overview

This rule validates whether a given string value meets a set of password requirements.

## Parameters

- `$min` (`int`): The minimum length of the password. Defaults to `12`.
- `$mixedCase` (`bool`): Whether the password must contain both uppercase and lowercase letters. Defaults to `false`.
- `$numbers` (`bool`): Whether the password must contain at least one number. Defaults to `false`.
- `$letters` (`bool`): Whether the password must contain at least one letter. Defaults to `false`.
- `$symbols` (`bool`): Whether the password must contain at least one symbol. Defaults to `false`.

## Examples

### Minimum length

```php
use Tempest\Validation\Rules\Password;

final class User
{
    #[Password(min: 8)]
    public string $password;
}
```

### Require mixed case

```php
use Tempest\Validation\Rules\Password;

final class User
{
    #[Password(mixedCase: true)]
    public string $password;
}
```

### Require numbers

```php
use Tempest\Validation\Rules\Password;

final class User
{
    #[Password(numbers: true)]
    public string $password;
}
```

### Require letters

```php
use Tempest\Validation\Rules\Password;

final class User
{
    #[Password(letters: true)]
    public string $password;
}
```

### Require symbols

```php
use Tempest\Validation\Rules\Password;

final class User
{
    #[Password(symbols: true)]
    public string $password;
}
```
