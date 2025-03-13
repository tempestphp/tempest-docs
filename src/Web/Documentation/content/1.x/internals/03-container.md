---
title: The container
category: internals
---

Here's a short summary of how the Tempest container works.

- Tempest doesn't have service providers like Laravel. Instead, it has **initializer** classes. Think of an initializer as a class that knows how to construct an object or interface.
- Tempest will discover these initializer classes for you. You don't need to register them anywhere.
- The container will also **autowire** as much as possible, so you only need initializer classes if you need to do manual setup work

Here's an example of a very simple initializer class:

```php
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

A couple of things to note about it:

- It has the `#[Singleton]` attribute, which means that this dependency will be registered as a **singleton**
- Tempest knows what kind of objects this initializer can create, based on the **return type**. This initializer will be used any time a `LoggerInterface` or `Logger` is requested from the container
- Again, you don't need to register this class anywhere. Tempest will discover it for you automatically

If you want to know more about initializer discovery in particular, you can check out `\Tempest\Container\InitializerDiscovery`
