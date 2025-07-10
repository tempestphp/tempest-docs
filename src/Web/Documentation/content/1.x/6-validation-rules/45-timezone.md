---
title: Timezone
description: "Validate that a value is a valid timezone."
---

## Overview

This rule validates whether a given string value is a valid timezone identifier.

## Parameters

- `$timezoneGroup` (`int`): A bitmask of `DateTimeZone` constants (e.g., `DateTimeZone::ASIA`, `DateTimeZone::PER_COUNTRY`). Defaults to `DateTimeZone::ALL`.
- `$countryCode` (`?string`): A two-letter ISO 3166-1 alpha-2 country code when `timezoneGroup` is `DateTimeZone::PER_COUNTRY`. Defaults to `null`.

## Examples

### Default

```php
use Tempest\Validation\Rules\Timezone;

final class User
{
    #[Timezone()]
    public string $timezone;
}
```

### With timezone group

```php
use Tempest\Validation\Rules\Timezone;
use const DateTimeZone::EUROPE;

final class User
{
    #[Timezone(timezoneGroup:EUROPE)]
    public string $timezone;
}
```

### With country code

```php
use Tempest\Validation\Rules\Timezone;
use const DateTimeZone::PER_COUNTRY;

final class User
{
    #[Timezone(timezoneGroup: PER_COUNTRY, countryCode: 'NL')]
    public string $timezone;
}
```
