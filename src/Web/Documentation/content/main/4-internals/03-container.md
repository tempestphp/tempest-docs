---
title: The container
description: "Tempest's container has a unique synergy with discovery. Learn how it differs from other frameworks' implementations."
---

In contrast to other frameworks, Tempest doesn't have a concept of service provider. Instead, the instantiation process of dependencies is specified in [initializer classes](../1-essentials/01-container#dependency-initializers), which are automatically [discovered](./02-discovery).

Additionally, the container will also [autowire](../1-essentials/01-container#injecting-dependencies) dependencies as much as possible, so initializer classes may only be used when there is the need for specific, manual set-up code.

The following is an example of an initializer:

```php LoggerInitializer.php
#[Singleton]
final readonly class LoggerInitializer implements Initializer
{
    public function initialize(Container $container): LoggerInterface|Logger
    {
        return new GenericLogger(
            $container->get(LogConfig::class),
        );
    }
}
```

Tempest knows that this initializer produces a `Logger` or `LoggerInterface` thanks to the return type of the `initialize()` method. The first time one of these objects is injected as a dependency, the container will call this initializer class.

Additionally, thanks to the `#[Singleton]` attribute, the logger instance will be registered as a singleton—which means this initializer will be called only once by the container.
