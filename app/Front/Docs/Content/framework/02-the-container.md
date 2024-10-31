---
title: The Container and Config
---

Tempest's dependency container is the heart of the framework. Anything you do framework related will be run through the container, meaning you'll have autowiring everywhere: from controllers to console commands, from event handlers to the command bus:

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;
use Tempest\Console\Console;

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
// app/Package.php

use Tempest\Console\Console;
use Tempest\Core\AppConfig;

final readonly class Package
{
    public function __construct(
        private Console $console,
        private AppConfig $config,
    ) {}
}
```

Tempest will automatically discover configuration files, please read the [Config](#config) section for more info.

## Dependency Initializers

When you need fine-grained control over how a dependency is constructed instead of relying on Tempest's autowiring capabilities, you can use initializer classes. Initializers are classes that know how to construct a specific class or interface. Whenever that class or interface is requested from the container, Tempest will use the initializer class to construct it.

Here's an example for a markdown initializer, this class will set up a markdown convertor, configure its extensions, and finally return the object that's resolved from the container. Whenever `{php}MarkdownConverter` is requested via the container, this initializer class will be used to construct it:

```php
// app/MarkdownInitializer.php

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
// app/MarkdownInitializer.php

use Tempest\Container\Container;
use Tempest\Container\Initializer;

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
// app/BlogRepositoryInitializer.php

use Tempest\Container\Container;
use Tempest\Container\Initializer;

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
// app/FileBlogRepository.php

use Tempest\Container\Autowire;

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
// app/Package.php

use Tempest\Container\Singleton;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

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
// app/MarkdownInitializer.php

use Tempest\Console\ConsoleCommand;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;

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
// app/WebHighlighterInitializer.php

use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;

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
// app/HttpExceptionHandler.php

use Tempest\Container\Tag;

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
// app/BookController.php

use Tempest\Http\Get;
use Tempest\Http\Response;

final readonly class BookController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
}
```

Since `$book` isn't a scalar value, Tempest will try to resolve `{php}Book` from the container whenever this controller action is invoked. This means we need an initializer that's able to match the `Book` model:

```php
// app/BookInitializer.php

use Tempest\Container\Container;
use Tempest\Container\Initializer;

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
// app/RouteBindingInitializer.php

use Tempest\Container\Container;
use Tempest\Container\DynamicInitializer;

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
// tempest/view/src/Renderers/BladeInitializer.php

use Tempest\Container\DynamicInitializer;
use Tempest\Container\Singleton;

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
    
    // …
}
```

## Built-in types dependencies

Besides being able to depend on objects, sometimes you'd want to depend on built-in types like `string`, `int` or more often `array`. It is possible to depend on these built-in types, but these cannot be autowired and must be initialized through a [tagged singleton](#tagged-singletons).

For example if we want to group a specific set of validators together as a tagged collection, you can initialize them in a tagged singleton initializer like so:

```php
// app/BookValidatorsInitializer.php

use Tempest\Container\Container;
use Tempest\Container\Initializer;

final readonly class BookValidatorsInitializer implements Initializer
{
    #[Singleton(tag: 'book-validators')]
    public function initialize(Container $container): array
    {
        return [
            $container->get(HeaderValidator::class),
            $container->get(BodyValidator::class),
            $container->get(FooterValidator::class),
        ];
    }
}
```

Now you can use this group of validators as a normal tagged value in your container:

```php
// app/BookController.php

use Tempest\Container\Tag;

final readonly class BookController
{
    public function __constructor(
        #[Tag('book-validators')] private readonly array $contentValidators,
    ) { /* … */ }
}
```

## Config

As mentioned, configuration is represented by objects in Tempest. Tempest provides many configuration classes for you, although the framework is designed to use them as little as possible. Whenever you need fine-grained control over part of the framework's config, you can overwrite Tempest's default config by creating one or more `*.config.php` files, anywhere in your project. Each `*.config.php` file should return one config object.

```php
// app/database.config.php

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

### Config Cache

Config files are cached by Tempest, you can read more about caching in the [dedicated chapter](/docs/framework/caching). You can enable or disable config cache with the `{txt}{:hl-property:CONFIG_CACHE:}` environment variable.

```env
{:hl-comment:# .env:}

{:hl-property:CONFIG_CACHE:}={:hl-keyword:true:}
```