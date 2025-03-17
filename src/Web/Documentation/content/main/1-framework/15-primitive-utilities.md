---
title: Primitive utilities
category: framework
---

Tempest comes with a handful of classes that improve working with primitive types such as strings and arrays. The most important feature is an object-oriented API around PHP's built-in primitive helper functions. Let's take a look at what's available.

## Strings

The `ImmutableString` and `MutableString` classes wraps a normal string, and provide a fluent API to manipulate that string.

```php
use Tempest\Support\Str\ImmutableString;

$slug = (new ImmutableString('https://tempestphp.com/docs/framework/14-primitive-helpers'))
    ->trim('/')
    ->afterLast('/')
    ->replaceRegex('/\d+-/', '')
    ->toString();
```

Note that you can also use the `str()` helper function, which is a shorthand for `ImmutableString`:

```php
use function Tempest\Support\str;

$path = str('https://tempestphp.com/docs/framework/14-primitive-helpers');

if (! $path->startsWith('/docs')) {
    // …
}
```

These string helpers encapsulate many of PHP's built-in string functions, as well as several regex-based functions. You can check out the [full API on GitHub](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Support/src/Str/ManipulatesString.php).

## Arrays

The `ImmutableArray` and `MutableArray` classes wrap an array, and provide a fluent API to manipulate it.

```php
use Tempest\Support\Arr\ImmutableArray;

$items = (new ImmutableArray(glob(__DIR__ . '/Content/*.md')))
    ->reverse()
    ->map(function (string $path) {
        // …
    })
    ->mapTo(BlogPost::class);
```

Note that you can also use the `arr()` helper function instead of manually creating an `ImmutableArray` object:

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

You can check out the [full API on GitHub](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Support/src/Arr/ManipulatesArray.php).

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
