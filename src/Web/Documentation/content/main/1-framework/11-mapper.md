---
title: Mapper
category: framework
---

Tempest comes with a flexible mapper component that can be used to map all sorts of data to objects and back. The mapper is internally used to handle persistence between models and the database, map PSR objects to internal requests, map request data to objects, and more.

Even better: the mapper is available for everyone to use. You can build your own mappers or rely on Tempest's built-in mappers.

## Mapping data

Let's consider a common example: mapping a json string to an object. You can write the following:

```php
use function Tempest\map;

$bookJson = getBookFromApi(/* … */);

$book = map($bookJson)->to(Book::class);
```

Note that the mapper provides static insights, so your IDE and static analyzers will know that `$book` is an instance of `Book`. Furthermore, you can also use it to map collections of data:

```php
use function Tempest\map;

$bookListJson = listBooksFromApi(/* … */);

$books = map($bookListJson)->collection()->to(Book::class);
```

Once again, your IDE and static analyzer will have the necessary insights to know that `$books` is an array of `Book` objects.

## Mapper discovery

Tempest will try its best to find the right mapper for you. All classes that implement the `\Tempest\Mapper\Mapper` interface will be automatically discovered and registered by Tempest. This means that, for example, you can map a JSON string or an array to an object, and Tempest will figure out how to do it for you:

```php
map($bookAsArray)->to(Book::class);

map($bookAsJson)->to(Book::class);
```

Mapper discovery relies on the `Mapper::canMap()` method, each mapper implementation must implement it, and return either `true` or `false` to determine whether this specific mapper can handle a specific case. Here's an example from Tempest's built-in mappers, where a mapper is used to map a PSR request to a Tempest request:

```php
final readonly class PsrRequestToRequestMapper implements Mapper
{
    public function canMap(mixed $from, mixed $to): bool
    {
        return $from instanceof PsrRequest && is_a($to, Request::class, true);
    }

    public function map(mixed $from, mixed $to): array|object
    { /* … */ }
}
```

## Choosing specific mappers

Sometimes, you don't want Tempest to decide which mapper should be used, but rather specify one yourself. That's done with the `map()->with()` method:

```php
$psrRequest = map($request)->with(RequestToPsrRequestMapper::class)->do();
```

Note that you need to call `->do()` to perform the actual mapping. You can define a chain of mappers as well:

```php
$psrRequest = map($data)->with(
    ArrayToObjectMapper::class,
    ObjectToRequestMapper::class,
    RequestToPsrRequestMapper::class,
)->do();
```

Next, you can also provide closures to the `with()` method. These closures expect the mapper as their first parameter, and `$from` as the second. By using closures you get access to the `$from` parameter as well, allowing you to do more advanced mapping via the `with()` method:

```php
$result = map(['a' => 'a', 'b' => 'b'])->with(
    fn (ArrayToObjectMapper $arrayToObject, mixed $from) => $arrayToObject->map($from, ObjectA::class),
    ObjectToArrayMapper::class,
    ArrayToJsonMapper::class,
)->do();
```

Finally, `map()->with()` can also be combined with `->collection()` and `->to()`:

```php
map(['a' => 'a', 'b' => 'b'])
    ->with(ArrayToObjectMapper::class)
    ->to(ObjectA::class);

map([
    ['a' => 'a', 'b' => 'b'],
    ['a' => 'c', 'b' => 'd'],
])
    ->with(ArrayToObjectMapper::class)
    ->collection()
    ->to(ObjectA::class);
```

## toArray and toJson

If you're using `map()->to()` instead of `map()->with()`, you'll always have to provide a target class for Tempest to map to. But what happens if you want to map an object to an array or json? In that case, you can use the `map()->toJson()` and `map()->toArray()` methods directly:

```php
$array = map($book)->toArray();

$json = map($book)->toJson();
```

## Overriding field names

Let's say you want to map an array of data to an object, but the array keys don't map one-to-one to the property names defined on your object:

```php
$book = map(['book_title' => 'Timeline Taxi'])->to(Book::class);
```

In this case, you can use the `#[MapFrom]` attribute:

```php
use Tempest\Mapper\MapFrom;

final class Book
{
    #[MapFrom('book_title')]
    public string $title;
}
```

Likewise, if you're mapping an object to an array or JSON, you can use to `#[MapTo]` attribute to map the property name to another key:

```php
use Tempest\Mapper\MapFrom;

final class Book
{
    #[MapTo('book_title')]
    public string $title;
}
```

## Strict mapping

If you're mapping between arrays and object, it's important to note that Tempest will always assume the object is the source of truth. It will also only take public properties into account. Protected and private properties will always be ignored by the array mapper.

Furthermore, by default, Tempest allows to build objects with missing data:

```php
final class Book
{
    public string $title;

    public string $contents;
}

$book = map(['title' => 'Timeline Taxi'])->to(Book::class); // This is allowed
```

Of course, accessing missing properties after the object has been constructed will result in an uninitialized property error. If you prefer a stricter mapping where Tempest throws an exception when data is missing, you can tag a property as `#[Strict]`:

```php
use Tempest\Mapper\Strict;

final class Book
{
    public string $title;

    #[Strict]
    public string $contents;
}

// Not allowed anymore, MissingValuesException will be thrown
$book = map(['title' => 'Timeline Taxi'])->to(Book::class);
```

You can also mark the whole class as `#[Strict]`:

```php
use Tempest\Mapper\Strict;

#[Strict]
final class Book
{
    public string $title;

    public string $contents;
}
```

## Casters

Sometimes you'll have to map textual data to complex data types. This is what casters are used for. A caster is called before writing data to a property. A common example is a caster that casts date strings to `DateTime` objects. Here's a simplified example:

```php
use Tempest\Mapper\Caster;

final readonly class DateTimeCaster implements Caster
{
    public function cast(mixed $input): DateTimeInterface
    {
        return new DateTimeImmutable($input);
    }
}
```

```php
use Tempest\Mapper\CastWith;

final class Book
{
    // …

    #[CastWith(DateTimeCaster::class)]
    public DateTimeImmutable $publishedAt;
}
```

Note that Tempest comes with built-in casters for several types already:

- Arrays
- Booleans
- DateTime and DateTimeImmutable
- Enums
- Floats
- Integers
- Value objects

This means you don't have to use an explicit `#[CastWith]` attribute on properties with those types. Of course, you _can_ still tag them with the `#[CastWith]` attribute if you'd like to use a different caster.

Furthermore, you can define custom global casters like so:

```php
use Tempest\Mapper\Casters\CasterFactory;

$container->get(CasterFactory::class)->addCaster(Carbon::class, CarbonCaster::class);
```

## Serializers

Similar to casters, serializers are used to map complex data into a serialized form. A common example is a serializer that casts `DateTime` objects to strings:

```php
use Tempest\Mapper\Serializer;
use Tempest\Mapper\Exceptions\CannotSerializeValue;

final readonly class DateTimeSerializer implements Serializer
{
    public function serialize(mixed $input): array|string
    {
        if (! $input instanceof DateTimeInterface) {
            throw new CannotSerializeValue(DateTimeInterface::class);
        }
        
        return $input->format(DATE_ATOM);
    }
}
```

```php
use Tempest\Mapper\SerializeWith;

final class Book
{
    // …

    #[SerializeWith(DateTimeSerializer::class)]
    public DateTimeImmutable $publishedAt;
}
```

Note that Tempest comes with built-in serializers for several types already:

- Arrays
- Booleans
- DateTime and DateTimeImmutable
- Enums
- Floats
- Integers
- Value objects

This means you don't have to use an explicit `#[SerializeWith]` attribute on properties with those types. Of course, you _can_ still tag them with the `#[SerializeWith]` attribute if you'd like to use a different serializer.

Furthermore, you can define custom global serializers like so:

```php
use Tempest\Mapper\Casters\SerializerFactory;

$container->get(SerializerFactory::class)->addSerializer(Carbon::class, CarbonSerializer::class);
```
