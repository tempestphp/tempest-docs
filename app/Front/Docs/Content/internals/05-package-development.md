---
title: Package Development
---

Tempest comes with a handful of tools to help third-party package developers.

## DoNotDiscover

Tempest has an attribute called `#[DoNotDiscover]`, which you can add on classes. Any class within your package that has this attribute won't be discovered by Tempest. You can still use that class internally, or allow you package to publish it (see [installers](#installers)).

```php
use Tempest\Core\DoNotDiscover;

#[DoNotDiscover]
final readonly class UserMigration implements Migration
{
    // …
}
```

## Installers

Packages can have one or more installers, which can be used to set up your package within a project. For example: a package can optionally publish migration files that will only be discovered when they are published. Take, for example, a look at the `AuthInstaller`, which will install the `User` model, as well as other related models and their migrations:

```php
use Tempest\Core\DoNotDiscover;
use Tempest\Core\Installer;
use Tempest\Core\PublishesFiles;
use Tempest\Generation\ClassManipulator;
use function Tempest\src_namespace;
use function Tempest\src_path;

final readonly class AuthInstaller implements Installer
{
    use PublishesFiles;

    public function getName(): string
    {
        return 'auth';
    }

    public function install(): void
    {
        $publishFiles = [
            __DIR__ . '/User.php' => src_path('User.php'),
            __DIR__ . '/UserMigration.php' => src_path('UserMigration.php'),
            __DIR__ . '/Permission.php' => src_path('Permission.php'),
            __DIR__ . '/PermissionMigration.php' => src_path('PermissionMigration.php'),
            __DIR__ . '/UserPermission.php' => src_path('UserPermission.php'),
            __DIR__ . '/UserPermissionMigration.php' => src_path('UserPermissionMigration.php'),
        ];

        foreach ($publishFiles as $source => $destination) {
            $this->publish(
                source: $source,
                destination: $destination,
            );
        }
    
        $this->publishImports();
    }
}
```

Running the installer looks like this:

```console
./tempest install auth

<h2>Running the `auth` installer, continue?</h2> [<u><em>yes</em></u>/no] 

<h2>app/User.php already exists. Do you want to overwrite it?</h2> [<u><em>yes</em></u>/no] 
<success>app/User.php created</success> 

<h2>app/UserMigration.php already exists. Do you want to overwrite it?</h2> [yes/<u><em>no</em></u>] 

<h2>app/Permission.php already exists. Do you want to overwrite it?</h2> [yes/<u><em>no</em></u>]
 
<h2>app/PermissionMigration.php already exists. Do you want to overwrite it?</h2> [<u><em>yes</em></u>/no] 
<success>app/PermissionMigration.php created</success> 

<h2>app/UserPermission.php already exists Do you want to overwrite it?</h2> [yes/<u><em>no</em></u>]
<success>Done</success> 
```

Note that you can use `src_path()` to generate paths to the project's source folder. This folder be either `src/` or `app/`, depending on the project's preferences. If you're using the `PublishesFiles` trait, then Tempest will also automatically adjust class namespaces and remove `#[DoNotDiscover]` attributes when publishing files.

On top of that, you can pass a callback to the `publish()` method, which gives you even more control over the published files:

```php
public function install(): void
{
    // …
    
    $this->publish(
        source: $source,
        destination: $destination,
        callback: function (string $source, string $destination): void {
            // …
        },
    );
    
    $this->publishImports();
}
```

Installers are resolved via the container, which means you can inject any dependency you need. You can also interact with the console from within the `install()` method if you want to (if you use the `PublishesFiles` trait, you have the console available automatically):

```php
final readonly class MyPackageInstaller implements Installer
{
    use PublishesFiles;

    public function getName(): string
    {
        return 'my-package';
    }

    public function install(): void
    {
        $base = $this->ask('Which subfolder do you want to store the files in?');
        
        $this->publish(
            source: __DIR__ . '/file.php',
            destination: src_path($base, 'file.php'),
            callback: function (string $source, string $destination): void {
                // …
            },
        );
        
        $this->publishImports();
    }
}
```

Finally, Installers will be discovered by Tempest, so you only need to implement the `\Tempest\Core\Installer` interface.

### Publishing imports

As you can see in the previous examples, `$this->publishImports()` is always called within the `install()` method. Calling this method will loop over all published files, and adjust any imports that reference to published files. 

## Provider classes

Unlike Symfony or Laravel, Tempest doesn't have a dedicated "service provider" concept. Instead, you're encouraged to rely on discovery and initializers. However, there might be cases where you need to "set up a bunch of things for your package", and you need a place to put that code. 

In order to do that, you're encouraged to simply have an event listener for the `KernelEvent::BOOTED` event. This event is triggered when Tempest's kernel has booted, but before any application code is run. It's the perfect place to hook into Tempest's internals if you need to set up stuff specifically for your package.

```php
use Tempest\Core\KernelEvent;
use Tempest\EventBus\EventHandler;

final readonly class MyPackageProvider
{
    public function __construct(
        // You can inject any dependency you like
        private Container $container,
    ) {}

    #[EventHandler(KernelEvent::BOOTED)]
    public function init(): void
    {
        // Do whatever needs to be done
        $this->container->…
    }
}
```

## Testing helpers

Tempest provides a class called `\Tempest\Framework\Testing\IntegrationTest`. Your PHPUnit tests can extend from it. By doing so, your tests will automatically boot the framework, and have a range of helper methods available.