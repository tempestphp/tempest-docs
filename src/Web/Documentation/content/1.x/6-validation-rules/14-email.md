---
title: Email
description: "Validate that a value is a valid email address."
---

## Overview

This rule validates whether a given string value is a valid email address according to RFC 5322.

## Parameters

- `$validationMethod` (`Egulias\EmailValidator\Validation\EmailValidation`): The validation method to use. Defaults to `new RFCValidation()`.

## Examples

### Default

```php
use Tempest\Validation\Rules\Email;

final class User
{
    #[Email()]
    public string $email;
}
```

### With validation method

```php
use Tempest\Validation\Rules\Email;
use Egulias\EmailValidator\Validation\DNSCheckValidation;

final class User
{
    #[Email(validationMethod: new DNSCheckValidation())]
    public string $email;
}
```
