---
title: IsEnum
description: "Validate that a value is a valid case for a given enum."
---

## Overview

This rule validates whether a given value is a valid case for a specific enum. You can also provide an array of cases to either include or exclude from validation.

## Parameters

- `$enum` (`string`): The enum class name.
- `$only` (`array`): An array of enum cases that should be considered valid. Defaults to an empty array.
- `$except` (`array`): An array of enum cases that should be considered invalid. Defaults to an empty array.

## Examples

```php
use Tempest\Validation\Rules\IsEnum;

enum Suit
{
    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;
}

final class Card
{
    #[IsEnum(Suit::class)]
    public string $suit;
}
```

### Only specific cases

```php
use Tempest\Validation\Rules\IsEnum;

final class Card
{
    #[IsEnum(Suit::class, only: [Suit::Hearts, Suit::Clubs])]
    public string $suit;
}
```

### Except specific cases

```php
use Tempest\Validation\Rules\IsEnum;

final class Card
{
    #[IsEnum(Suit::class, except: [Suit::Spades])]
    public string $suit;
}
```
