---
title: Primitive utilities
category: framework
---

Tempest comes with a handful of classes that improve working with primitive types such as strings and arrays. The most important feature is an object-oriented API around PHP's built-in primitive helper functions. Let's take a look at what's available.

## Strings

The `StringHelper` class wraps a normal string, and provides a fluent API to manipulate that string. Note that all these methods are immutable, so every call creates a new instance of `StringHelper` without modifying the original one.

```php
use Tempest\Support\StringHelper;

$slug = (new StringHelper('https://tempestphp.com/docs/framework/14-primitive-helpers'))
    ->trim('/')
    ->afterLast('/')
    ->replaceRegex('/\d+-/', '')
    ->toString();

// primitive-helpers
```

Note that you can also use the `str()` helper function instead of manually creating a `StringHelper` object:

```php
use function Tempest\Support\str;

$path = str('https://tempestphp.com/docs/framework/14-primitive-helpers');

if (! $path->startsWith('/docs')) {
    // …
}
```

The `StringHelper` encapsulates many of PHP's built-in string functions, as well as several regex-based functions. You can check out the [full API on GitHub](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Support/src/StringHelper.php).

## Arrays

The `ArrayHelper` class wraps a normal array, and provides a fluent API to manipulate it. Note that all these methods are immutable, so every call creates a new instance of `ArrayHelper` without modifying the original one.

```php
use Tempest\Support\ArrayHelper;

$items = (new ArrayHelper(glob(__DIR__ . '/Content/*.md')))
    ->reverse()
    ->map(function (string $path) {
        // …
    })
    ->mapTo(BlogPost::class);
```

Note that you can also use the `arr()` helper function instead of manually creating a `ArrayHelper` object:

```php
use function Tempest\Support\arr;

$codeBlocks = arr(glob(__DIR__ . '/*.md'))
    ->mapWithKeys(function (string $path) {
        preg_match('/(\d+).md/', $path, $matches);

        $index = $matches[1];

        yield "code{$index}" => $this->markdown->convert(file_get_contents($path));
    })
    ->toArray();
```

You can check out the [full API on GitHub](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Support/src/ArrayHelper.php).

## Enums

The `IsEnumHelper` traits provides a bunch of useful methods that can be added to any enum:

```php
use Tempest\Support\IsEnumHelper;

enum MyEnum
{
    use IsEnumHelper;

    case FOO;
    case BAR;
}

MyEnum::FOO->is(MyEnum::BAR);
MyEnum::names();

// …
```

You can check out the [full API on GitHub](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Support/src/IsEnumHelper.php).
