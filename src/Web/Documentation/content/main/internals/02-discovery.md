---
title: Discovery
category: internals
---

Tempest has a unique design when it comes to bootstrapping an application, more specifically when it comes to loading framework related code. Instead of having to manually registering project code or packages, Tempest will scan your codebase and automatically detect code that should be loaded. This concept is called **Discovery**.

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

And another example: initializers are discovered based on the `Initializer` interface, together with their return type:

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
use Tempest\Core\Discovery;
use Tempest\Core\DiscoveryLocation;
use Tempest\Core\IsDiscovery;
use Tempest\Reflection\ClassReflector;
use Tempest\EventBus\EventBusConfig;

final readonly class EventBusDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        // Discovery classes are autowired,
        // so you can inject all dependencies you need
        private EventBusConfig $eventBusConfig,
    ) {
    }

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $eventHandler = $method->getAttribute(EventHandler::class);

            // Extra checks to determine whether
            // we can actually use the current method as an event handler

            // …

            // Finally, we add all discovery-related data into `$this->discoveryItems`:
            $this->discoveryItems->add($location, [$eventName, $eventHandler, $method]);
        }

        // Next, the `apply` method is called whenever discovery is ready to be applied into the framework.
        // In this case, we want to loop over all registered discovery items, and add them into the event bus config.
        public function apply(): void
        {
            foreach ($this->discoveryItems as [$eventName, $eventHandler, $method]) {
                $this->eventBusConfig->addClassMethodHandler(
                    event: $eventName,
                    handler: $eventHandler,
                    reflectionMethod: $method,
                );
            }
        }
    }
}
```

## Discovery on files instead of classes

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
use Tempest\Core\Discovery;
use Tempest\Core\DiscoversPath;
use Tempest\Core\DiscoveryLocation;
use Tempest\Core\IsDiscovery;
use Tempest\Reflection\ClassReflector;
use Tempest\View\ViewConfig;
use Tempest\View\Components\AnonymousViewComponent;

final readonly class ViewComponentDiscovery implements Discovery, DiscoversPath
{
    use HandlesDiscoveryCache;

    public function __construct(
        private ViewConfig $viewConfig,
    ) {
    }

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        // …
    }

    public function discoverPath(DiscoveryLocation $location, string $path): void
    {
        if (! str_ends_with($path, '.view.php')) {
            return;
        }

        // …

        $this->discoveryItems->add($location, [
            $matches['name'],
            new AnonymousViewComponent(
                contents: $matches['header'] . $matches['view'],
                file: $path,
            ),
        ]);
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as [$name, $viewComponent]) {
            if (is_string($viewComponent)) {
                $viewComponent = new ClassReflector($viewComponent);
            }

            $this->viewConfig->addViewComponent(
                name: $name,
                viewComponent: $viewComponent,
            );
        }
    }
}
```
