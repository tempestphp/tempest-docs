---
title: Truly decoupled discovery
description: Tempest's discovery can now be used in any project
tag: release
author: brent
---

Making the Tempest components work in all types of projects has been a goal from the very start of the framework. For example, [`tempest/view`](/3.x/essentials/views#tempest-view-as-a-standalone-engine) can already be plugged into any project or framework you'd like. 

Today we're making another component truly standalone: [`tempest/discovery`](/3.x/essentials/discovery). Discovery is what powers Tempest: it reads all your project and vendor code and configures that code in a PSR-11 compliant container for you. It's a simple idea, but really powerful when put into practice. And while frameworks like Symfony and Laravel have similar capabitilies for framework-specific classes, Tempest's discovery is built to be extensible for all code.

In this blog post, I'll show you how to use `tempest/discovery` in any project, with any type of container, and I'll explain the impact for existing Tempest applications.

## Using discovery

You start by requiring `tempest/discovery` in any project, it could be a framework like Symfony or Laravel, a vanilla PHP app, anything.

```console
composer require tempest/discovery
```

The next step is to have a PSR-11 container. You can think of discovery as an extension for containers. In this case we can use the `php-di` container. If you're working within another framework like Laravel or Symfony, their containers already implement PSR-11 and you can use them directly.

```console
composer require php-di/php-di
```

The next step is to boot discovery. This means discovery will scan all your project and vendor files and pass them to discovery classes to be processed.  

```php ./index.php
use Tempest\Discovery\BootDiscovery;
use Tempest\Discovery\DiscoveryConfig;
use DI\Container;

// Usually this container is already provided by whatever framework you're using
$container = new Container();

new BootDiscovery(
    container: $container,
    config: DiscoveryConfig::autoload(__DIR__),
)();
```

As a shorthand, `DiscoveryConfig::autoload(__DIR__)` will check the provided path for a `composer.json` file, and find scannable locations based on that. You can, of course, manually provide locations to scan as well:

```php
use Tempest\Discovery\DiscoveryConfig;
use Tempest\Discovery\DiscoveryLocation;
// …

$config = new DiscoveryConfig(locations: [
    new DiscoveryLocation('App\\', 'app/'),
]);

new BootDiscovery(
    container: $container,
    config: $config,
)();
```

That's all for the basic setup. If you want more complex configuration and learn about caching, head over to [the discovery docs](/3.x/essentials/discovery#discovery-as-a-standalone-package). Now that we've set discovery up, though, what exactly can you do with it?

### An example

Let's say you're building an event-sourced system where "projectors" can be used to replay all previously stored events. You want to build a command that shows all available projectors where the user wants to select all relevant projectors. Furthermore, whenever an event is dispatched, you need to loop over that same list of projectors to find out which events should be passed to which ones. 

The interface would look something like this:

```php
interface Projector
{
    public function dispatch(object $event): void;

    public function clear(): void;
}
```

And a (simplified) implementation could look like this:

```php
final class VisitsPerDayProjector implements Projector
{
    public function onPageVisited(PageVisited $pageVisited): void
    {
        // Perform the necessary queries for this projector.
    }

    public function dispatch(object $event): void
    {
        if ($event instanceof PageVisited) {
            $this->onPageVisited($event);
        }
    }

    public function clear(): void
    {
        // Clear the projector to be rebuilt from scratch
    }
}
```

In other words: we need a list of classes that implement the `Projector` interface. This is where discovery comes in. A discovery class implements the {b`Tempest\Discovery\Discovery`} interface, which themselves are discovered as well. No need to register them anyway; disvovery takes care of it for you.

```php src/Discovery/ProjectorDiscovery.php
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class ProjectorDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly ProjectorConfig $config,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->implements(Projector::class)) {
            $this->discoveryItems->add($location, $class);
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as $class) {
            $this->config->projectors[] = $class->getName();
        }
    }
}
```

This discovery class will take care of registering all projectors in whatever directories you specified at the start. It will store them in an object `ProjectorConfig`, which we assume is registered as a singleton in the container — meaning it's accisable throughout the rest of your codebase, and you can inject it anywhere you want. For example, in that console command:

```php
final readonly class EventsReplayCommand
{
    use HasConsole;

    public function __construct(
        private ProjectorConfig $projectorConfig,
    ) {}

    #[ConsoleCommand]
    public function __invoke(?string $replay = null): void
    {
        foreach ($this->projectorConfig->projectors as $projectorClass) {
            // …
        }   
    }
}
```

In an event bus middleware:

```php
final readonly class StoredEventMiddleware implements EventBusMiddleware
{
    public function __construct(
        private ProjectorConfig $projectorConfig,
    ) {}

    #[Override]
    public function __invoke(string|object $event, EventBusMiddlewareCallable $next): void
    {
        // …
        
        foreach ($this->projectorConfig->projectors as $projectorClass) {
            // Dispatch the event to the relevant projectors
        }
    }
}
```

Or anywhere else. Zero config needed. That's the power of discovery.

### What else?

What else can you do with discovery? Basically anything you can imagine that you don't want to configure manually. In Tempest, we use it to discover routes, console commands, database migrations, objects marked for TypeScript generation, static pages, event listeners, command handlers, and a lot more.

The concept of discovery isn't new; other frameworks have proven that it's a super convenient way to write code. Tempest simply takes it to the next level and allows you to use it in any project you want — that's because Tempest truly gets out of your way 😁

## Impact on Tempest projects

We had to do a small refactor to make discovery truly standalone. In theory, you shouldn't be affected by these changes, unless your Tempest project was fiddling with some lower-level framework components. Luckily, you're not on your own. As with every Tempest upgrade, we make the process as easy as possible with Rector.

For starters, install Rector if you haven't yet:

```
composer require rector/rector --dev 
vendor/bin/rector
```

Next, update Tempest; it's important to add the `--no-scripts` flag to prevent any errors from being thrown during the update.

```sh
composer require tempest/framework:^3.4 --no-scripts
```

Then configure Rector to upgrade to Tempest 3.4:

```php
// rector.php

use \Tempest\Upgrade\Set\TempestSetList;

return RectorConfig::configure()
    // …
    ->withSets([TempestSetList::TEMPEST_34]);
```

Next, run Rector:

```
vendor/bin/rector
```

Finally: clear config and discovery caches, and regenerate discovery:

```
rm -r .tempest/cache/config
rm -r .tempest/cache/discovery
./tempest discovery:generate
```

And that's it! Just in case you want to know all the details of this refactor, you can head over to [the pull request](https://github.com/tempestphp/tempest-framework/pull/2041) to see a list of changes that might affect you.

## In closing

The Tempest community has been using discovery for years, and without any exception, everyone simply loves how frictionless their development workflow has become because of it. Of course there's more to learn on how to configure discovery and setup caching, so head over to [the discovery docs](/3.x/essentials/discovery) to learn more.

Finally, come [join our Discord](/discord) if you're interested in Tempest or want to further talk about discovery. We'd love to hear from you!