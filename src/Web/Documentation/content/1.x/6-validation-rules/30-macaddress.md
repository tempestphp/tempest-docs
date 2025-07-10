---
title: MACAddress
description: "Validate that a value is a valid MAC address."
---

## Overview

This rule validates whether a given string value is a valid MAC address.

## Example

```php
use Tempest\Validation\Rules\MACAddress;

final class Device
{
    #[MACAddress()]
    public string $macAddress;
}
```
