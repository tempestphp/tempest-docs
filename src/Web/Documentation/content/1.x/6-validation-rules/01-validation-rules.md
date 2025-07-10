---
title: Validation Rules
---

## Overview

Tempest's validation rules are located in the `Tempest\Validation\Rules` namespace and can be used as attributes on class properties. For detailed information on how to use validation rules, see the [Validation documentation](../2-features/03-validation.md#adding-more-rules).

## Available rules

Tempest provides a comprehensive set of validation rules that can be used with attributes on your class properties. Below is a complete list organized by category:

### String Validation

- **[Alpha](03-alpha.md)** - Validate that a value contains only alphabetic characters
- **[AlphaNumeric](04-alpha-numeric.md)** - Validate that a value contains only alphanumeric characters
- **[DoesNotEndWith](12-does-not-end-with.md)** - Validate that a value does not end with a specific string
- **[DoesNotStartWith](13-does-not-start-with.md)** - Validate that a value does not start with a specific string
- **[EndsWith](15-ends-with.md)** - Validate that a value ends with a specific string
- **[IsString](26-is-string.md)** - Validate that a value is a string
- **[Length](28-length.md)** - Validate the length of a string
- **[Lowercase](29-lowercase.md)** - Validate that a string is lowercase
- **[NotEmpty](32-not-empty.md)** - Validate that a string is not empty
- **[Numeric](35-numeric.md)** - Validate that a value contains only numeric characters
- **[RegEx](39-reg-ex.md)** - Validate that a value matches a regular expression
- **[StartsWith](42-starts-with.md)** - Validate that a value starts with a specific string
- **[Uppercase](47-uppercase.md)** - Validate that a string is uppercase

### Numeric Validation

- **[Between](07-between.md)** - Validate that a value is between a minimum and maximum value
- **[DivisibleBy](11-divisible-by.md)** - Validate that a value is divisible by a specific number
- **[Even](16-even.md)** - Validate if the value is an even number
- **[IsFloat](24-is-float.md)** - Validate that a value is a float
- **[IsInteger](25-is-integer.md)** - Validate that a value is an integer
- **[MultipleOf](31-multiple-of.md)** - Validate that a value is a multiple of a specific number
- **[Odd](36-odd.md)** - Validate if the value is an odd number

### Date & Time Validation

- **[AfterDate](02-after-date.md)** - Validate that a date is after a specific date
- **[BeforeDate](06-before-date.md)** - Validate that a date is before a specific date
- **[BetweenDates](08-between-dates.md)** - Validate that a date is between two other dates
- **[DateTimeFormat](10-date-time-format.md)** - Validate that a value is a date in a specific format
- **[Time](43-time.md)** - Validate that a value is a valid time
- **[Timestamp](44-timestamp.md)** - Validate that a value is a valid timestamp
- **[Timezone](45-timezone.md)** - Validate that a value is a valid timezone

### Array & Collection Validation

- **[ArrayList](05-array-list.md)** - Validate that a value is an array list
- **[Count](09-count.md)** - Validate the number of items in an array
- **[In](18-in.md)** - Validate that a value is one of a given set of values
- **[NotIn](33-not-in.md)** - Validate that a value is not one of a given set of values

### Network & Format Validation

- **[Email](14-email.md)** - Validate that a value is a valid email address
- **[HexColor](17-hex-color.md)** - Validate that a value is a valid hexadecimal color
- **[IP](19-ip.md)** - Validate that a value is a valid IP address
- **[IPv4](20-ipv4.md)** - Validate that a value is a valid IPv4 address
- **[IPv6](21-ipv6.md)** - Validate that a value is a valid IPv6 address
- **[Json](27-json.md)** - Validate that a value is a valid JSON string
- **[MACAddress](30-macaddress.md)** - Validate that a value is a valid MAC address
- **[PhoneNumber](38-phone-number.md)** - Validate that a value is a valid phone number
- **[Ulid](46-ulid.md)** - Validate that a value is a valid ULID
- **[Url](48-url.md)** - Validate that a value is a valid URL
- **[Uuid](49-uuid.md)** - Validate that a value is a valid UUID

### Type & Value Validation

- **[IsBoolean](22-is-boolean.md)** - Validate that a value is a boolean
- **[IsEnum](23-is-enum.md)** - Validate that a value is a valid case for a given enum
- **[NotNull](34-not-null.md)** - Validate that a value is not null
- **[ShouldBeFalse](40-should-be-false.md)** - Validate that a value is false
- **[ShouldBeTrue](41-should-be-true.md)** - Validate that a value is true

### Security Validation

- **[Password](37-password.md)** - Validate that a string is a valid password
