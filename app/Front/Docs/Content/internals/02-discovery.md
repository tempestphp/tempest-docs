---
title: Discovery
---

Tempest uses a pretty unique design when it comes to bootstrapping an application, more specifically when it comes to loading framework related code. Instead of having to manually registering project code or packages, Tempest will scan your codebase and automatically detect code that should be loaded. This concept is called **Discovery**.

Discovery is powered by composer metadata: all packages that depend on Tempest, as well as your project code, will be passed to Tempest's discovery. Discovery uses a variety of rules to determine which code does what. Discovery can look at file names, attributes, interfaces, return types, and more. For example, web routes are discovered based on route attributes:

```php
final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('home.view.php');
    }
}
```

View components in turn are discovered by containing a `{html}<x-component>` tag:

```html
<x-component name="x-base">
```

And initializers are discovered based on the `Initializer` interface, together with their return type:

```php
final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter
    {
        // …
    }
}
```

You might think that scanning your codebase takes a while, but Tempest will cache discovery information so that it's as performant as possible.

## Built-in discovery classes

Tempest provides a handful of discovery classes out of the box.

- `CommandBusDiscovery` discovers methods with the `#[CommandHandler]` attribute and registers them into the command bus.
- `ConsoleCommandDiscovery` discovers methods with the `#[ConsoleCommand]` attribute and registers them as console commands.
- `ScheduleDiscovery` discovers methods with the `#[Schedule]` attribute and registers them as scheduled tasks.
- `InitializerDiscovery` discovers classes that implement `\Tempest\Container\Initializer` or `\Tempest\Container\DynamicInitializer` and registers them in the container.
- `MigrationDiscovery` discovers classes that implement `\Tempest\Database\Migration` and registers them in the migration manager.
- `DiscoveryDiscovery` discovers other discovery classes. This class is run manually by the framework when booted.
- `EventBusDiscovery` discovers methods with the `#[EventHandler]` attribute and registers them in the event bus.
- `RouteDiscovery` discovers route attributes on methods and registers them as controller actions in the router.
- `MapperDiscovery` discovers classes that implement `\Tempest\Mapper\Mapper`, which are registered in `\Tempest\Mapper\ObjectFactory`
- `ViewComponentDiscovery` discovers classes that implement `\Tempest\View\ViewComponent`, as well as view files that contain `{html}<x-component>`

## Building your own discovery classes

Since Tempest uses discovery to discover discovery classes, the only thing you need to do is provide a class that implements `\Tempest\Discovery\Discovery`, and Tempest will do the rest.

Here's for example a simplified event bus discovery implementation:

```php
final readonly class EventBusDiscovery implements Discovery
{
    // Use this trait to simplify discovery caching
    use HandlesDiscoveryCache;

    public function __construct(
        // Discovery classes are autowired,
        // so you can inject all dependencies you need
        private EventBusConfig $eventBusConfig,
    ) {
    }

    public function discover(ClassReflector $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $eventHandler = $method->getAttribute(EventHandler::class);

            // Extra checks to determine whether
            // we can actually use the current method as an event handler
            
            // …
            
            // Finally, we register this method in the event bus config.
            // This config object is used in turn by the event bus 
            $this->eventBusConfig->addHandler(
                eventHandler: $eventHandler,
                eventName: $type->getName(),
                method: $method,
            );
        }
    }

    // In order to cache this discovery class, we need to provide a payload.
    // In this case, we should serialize the registered handlers from the event bus config class
    public function createCachePayload(): string
    {
        return serialize($this->eventBusConfig->handlers);
    }

    // To restore a cached discovery class, we need to unserialize that cached payload
    // and place it back into the event bus config
    public function restoreCachePayload(Container $container, string $payload): void
    {
        $handlers = unserialize($payload);

        $this->eventBusConfig->handlers = $handlers;
    }
}
```

### Discovery on files instead of classes

In some cases, you want to not just discover classes, but also files. Think about view components:

```html
<x-component name="x-base">
    <html lang="en">
        <head>
            <title :if="$this->title">{{ $this->title }} | Tempest</title>
            <title :else>Tempest</title>
        </head>
        <body>
    
        <x-slot />
    
        </body>
    </html>
</x-component>
```

In this case, you can implement the additional `\Tempest\Discovery\DiscoversPath` interface. It will allow a discovery class to discover all paths that aren't classes as well:

```php

final readonly class ViewComponentDiscovery implements Discovery, DiscoversPath
{
    use HandlesDiscoveryCache;

    public function __construct(
        private ViewConfig $viewConfig,
    ) {
    }

    public function discover(ClassReflector $class): void
    {
        // …
    }

    public function discoverPath(string $path): void
    {
        if (! str_ends_with($path, '.view.php')) {
            return;
        }

        // …

        $this->viewConfig->addViewComponent(
            name: $matches['name'],
            viewComponent: new AnonymousViewComponent(
                contents: $matches['header'] . $matches['view'],
                file: $path,
            ),
        );
    }
```