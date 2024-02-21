```php
use Tempest\Interface\Package;

final readonly class MyPackage implements Package
{
    public function getPath(): string
    {
        return __DIR__;
    }

    public function getNamespace(): string
    {
        return 'Vendor\\Package\\';
    }

    public function getDiscovery(): array
    {
        return [];
    }
}
```

```php
// In tempest and/or public/index.php
$appConfig = new AppConfig(
    environment: Environment::from(env('ENVIRONMENT')),
    discoveryCache: env('DISCOVERY_CACHE'),
    packages: [
        new TempestPackage(),
        new AppPackage(),
        new MyPackage(),
    ],
);

Tempest::boot($appConfig)->console()->run();
Tempest::boot($appConfig)->http()->run();
```

Tempest will detect and register all commands, controllers, etc. from within your package.