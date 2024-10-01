---
title: The Container and Config
---

Tempest's dependency container is the heart of the framework. Anything you do framework related will be run through the container, meaning you'll have autowiring everywhere: from controllers to console commands, from event handlers to the command bus:

```php
use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    public function __construct(
        private Console $console,
    ) {}
    
    #[ConsoleCommand]
    public function all(): void 
    {
        // …
    }
}
```

On top of that, Tempest configuration files are PHP objects, meaning they'll be registered in the container as singletons as well:

```php

final readonly class Package
{
    public function __construct(
        private Console $console,
        private AppConfig $config,
    ) {}
}
```

Tempest will automatically discover configuration files, please read the [Config](#content-config) section for more info.

## Dependency Initializers

When you need fine-grained control over how a dependency is constructed instead of relying on Tempest's autowiring capabilities, you can use initializer classes. Initializers are classes that know how to construct a specific class or interface. Whenever that class or interface is requested from the container, Tempest will use the initializer class to construct it.

Here's an example for a markdown initializer, this class will set up a markdown convertor, configure its extensions, and finally return the object that's resolved from the container. Whenever `{php}MarkdownConverter` is requested via the container, this initializer class will be used to construct it:

```php
use Tempest\Container\Container;
use Tempest\Container\Initializer;

final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter
    {
        $environment = new Environment();

        $highlighter = (new Highlighter(new CssTheme()));

        $highlighter
            ->addLanguage(new TempestViewLanguage())
            ->addLanguage(new TempestConsoleWebLanguage())
            ->addLanguage(new ExtendedJsonLanguage())
        ;

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FrontMatterExtension())
            ->addRenderer(FencedCode::class, new CodeBlockRenderer($highlighter))
            ->addRenderer(Code::class, new InlineCodeBlockRenderer($highlighter))
        ;

        return new MarkdownConverter($environment);
    }
}
```

Note that initializers are discovered by Tempest. The only thing you need to do is have a class implement `{php}Initializer`, and configure the return type of the `{php}initialize()` method. It's the return type that's used by the container to determine which class or interface this initializer provides.

Initializers can also return union types, meaning the container can match several classes to a single initializer:

```php
final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter|Markdown
    {
        // …
    }
}
```

## Autowired dependencies

Oftentimes, you want to link a default implementation to an interface. In these cases, it might feel like overhead to create an initializer class with one line of code:

```php
final readonly class BlogRepositoryInitializer implements Initializer
{
    public function initialize(Container $container): BlogRepository
    {
        return new FileBlogRepository();
    }
}
```

For simple one-to-one mappings, you can skip the initializer, and use the `#[Autowire]` attribute instead. Add the attribute to the default implementation, and Tempest will link that class to whatever interface it implements: 

```php
#[Autowire]
final readonly class FileBlogRepository implements BlogRepository
{
    // …
}
```

(Note: autowired classes can also be defined as singletons, keep on reading.)

## Singletons

If you need to register a class as a singleton in the container, you can use the `{php}#[Singleton]` attribute. Any class can have this attribute:

```php
#[Singleton]
final readonly class Package
{
    public function __construct(
        private Console $console,
    ) {}
    
    #[ConsoleCommand]
    public function all(): void {}
}
```

Furthermore, an initializer method can be annotated as a singleton, meaning its return object will be registered as a singleton:

```php
final readonly class MarkdownInitializer implements Initializer
{
    #[Singleton]
    public function initialize(Container $container): MarkdownConverter|Markdown
    {
        // …
    }
}
```

### Tagged singletons

In some cases, you want more control over singleton definitions. Let's say you need two singleton variants of the same class. More concrete: you want an instance of `{php}\Tempest\Hihglight\Highlighter` that's configured for web highlighting, and one for CLI highlighting. This is where tagged singletons come in. The `{php}#[Singleton]` attribute can receive an optional `$tag` parameter, which is used to tag this specific singleton initializer:

```php
final readonly class WebHighlighterInitializer implements Initializer
{
    #[Singleton(tag: 'web')]
    public function initialize(Container $container): Highlighter
    {
        return new Highlighter(new CssTheme());
    }
}
```

Retrieving this specific instance from the container can be done by using the `{php}#[Tag]` attribute during autowiring:

```php
class HttpExceptionHandler implements ExceptionHandler
{
    public function __construct(
        #[Tag('web')] private Highlighter $highlighter,
    ) {}
}
```

You can also get it from the container directly like so:

```php
$container->get(Highlighter::class, tag: 'cli');
```

You can read [this blog post](https://stitcher.io/blog/tagged-singletons) for a more in-depth explanation on tagged singletons.

## Dynamic initializers

Some edge cases require more flexibility to match a requested class to an initializer. Let's take the example of route model binding. Let's say you have a controller like this: 

```php
final readonly class BookController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
}
```

Since `$book` isn't a scalar value, Tempest will try to resolve `{php}Book` from the container whenever this controller action is invoked. This means we need an initializer that's able to match the `Book` model:

```php
final class BookInitializer implements Initializer
{
    public function initialize(Container $container): Book
    {
        // …
    }
} 
```

While this approach works, it would be very inconvenient to create an initializer for every model class. Furthermore, we want route binding to be provided by the framework, so we need a more generic approach. In essence, we need a way of using this initializer whenever a class is requested the implements `{php}Model`. That's where `{php}DynamicInitializer` comes in: this interfaces allows you to do dynamic matching on class names, instead of simply using the return type of the `{php}initialize()` method:

```php
final class RouteBindingInitializer implements DynamicInitializer
{
    public function canInitialize(string $className): bool
    {
        return is_a($className, Model::class, true);
    }

    public function initialize(string $className, Container $container): object
    {
        // …
    }
}
```

While dynamic initializers aren't often required, they are useful to some edge cases like these. Another example of a dynamic initializer is the `{php}BladeInitializer`, which should only be used whenever the blade package is installed. It looks like this:

```php
#[Singleton]
final readonly class BladeInitializer implements DynamicInitializer
{
    public function canInitialize(string $className): bool
    {
        if (! class_exists('\Jenssegers\Blade\Blade')) {
            return false;
        }

        return $className === Blade::class;
    }
}
```

## Config

As mentioned, configuration is represented by objects in Tempest. Tempest provides many configuration classes for you, although the framework is designed to use them as little as possible. Whenever you need fine-grained control over part of the framework's config, you can create a `Config` folder in your main project folder. This folder can contain plain PHP files, each file can return a config instance:

```php
// app/Config/database.php
<?php

use Tempest\Database\DatabaseConfig;
use Tempest\Database\Drivers\MySqlDriver;
use function Tempest\env;

return new DatabaseConfig(
    driver: new MySqlDriver(
        host: env('DB_HOST')
        port: env('DB_PORT')
        username: env('DB_USERNAME')
        password: env('DB_PASSWORD'),
        database: env('DB_NAME'),
    ),
);
```

Project-level configuration files will be discovered automatically, and will overwrite Tempest's default config. In this example, the default `DatabaseConfig` object will be overwritten by your custom one, using MySQL instead of SQLite, and retrieving its credentials from environment variables.