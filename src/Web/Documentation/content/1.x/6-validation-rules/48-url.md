---
title: Url
description: "Validate that a value is a valid URL."
---

## Overview

This rule validates whether a given string value is a valid URL. You can optionally provide a list of allowed protocols.

## Parameters

- `$protocols` (`string[]`): An array of allowed protocols (e.g., `http`, `https`, `ftp`). Defaults to an empty array, which allows all common protocols.

## Examples

### Default

```php
use Tempest\Validation\Rules\Url;

final class Link
{
    #[Url()]
    public string $url;
}
```

### With specific protocols

```php
use Tempest\Validation\Rules\Url;

final class Link
{
    #[Url(['ftp', 'ftps'])]
    public string $url;
}
```
