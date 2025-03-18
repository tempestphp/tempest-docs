---
title: Caching
category: framework
---

Tempest comes with a simple wrapper around PSR-6, which means you can use all PSR-6 compliant cache pools. Tempest uses [Symfony's Cache Component](https://symfony.com/doc/current/components/cache.html) as a default implementation, so all of [Symfony's adapters](https://symfony.com/doc/current/components/cache.html#available-cache-adapters) are available out of the box.

## Configuration

Tempest will use file caching by default. You can however configure another adapter via `CacheConfig`:

```php
// app/Config/cache.config.php

use Tempest\Cache\CacheConfig;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return new CacheConfig(
    pool: new FilesystemAdapter(
        namespace: '',
        defaultLifetime: 0,
        directory: __DIR__ . '/../../../../.cache',
    ),
);
```

## Caching

To be able to cache stuff, you need to inject the `Cache` interface wherever you need it, and you're ready to go:

```php
// app/RssController.php

use Tempest\Cache\Cache;
use Tempest\Router\Response;

final readonly class RssController
{
    public function __construct(
        private Cache $cache
    ) {}

    public function __invoke(): Response
    {
        $rss = $this->cache->resolve(
            key: 'rss',
            cache: function () {
                return file_get_contents('https://stitcher.io/rss')
            },
            expiresAt: new DateTimeImmutable()->add(new DateInterval('P1D'))
        )
    }
}
```

If you need more fine-grained control, the `Cache` interface has the following methods:

- `resolve({:hl-type:string:} $key, {:hl-type:Closure:} $cache, {:hl-type:?DateTimeInterface:} $expiresAt = {:hl-keyword:null:}): {:hl-type:mixed:}` — to retrieve an item from cache, it'll be cached automatically if it wasn't yet.
- `put({:hl-type:string:} $key, {:hl-type:mixed:} $value, {:hl-type:?DateTimeInterface:} $expiresAt = {:hl-keyword:null:}): {:hl-type:CacheItemInterface:}` — to get an item from cache. Note that this method will always return `CacheItemInterface`, even if there wasn't a hit (thanks PSR-6!). Use `$item->isHit()` to know whether it's valid or not.
- `get({:hl-type:string:} $key): {:hl-type:mixed:}` — get an item from cache, returns `null` if there wasn't a hit.
- `remove({:hl-type:string:} $key): {:hl-type:void:}` — removes an item from cache.
- `clear(): {:hl-type:void:}` — clears the cache in full.

## Custom caches

It's important to note that the pool configured in `CacheConfig` is used for the default cache, also known as the **project cache**. If needed, you can create your own caches with different adapters like so:

```php
// app/RedisCache.php

use Tempest\Container\Singleton;
use Tempest\Cache\Cache;
use Tempest\Cache\IsCache;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

#[Singleton]
final readonly class RedisCache implements Cache
{
    use IsCache;

    protected function getCachePool(): CacheItemPoolInterface
    {
        return new RedisAdapter(/* … */)
    }
}
```

In fact, Tempest comes with two other internal caches besides the project cache: `ViewCache` and `DiscoveryCache`. Both caches are used for internally by the framework, but can of course be cleared by users as well.

## Clearing caches

Tempest comes with a `cache:clear` command which allows you to pick which caches you want to clear:

```console
./tempest cache:clear

<h2>Which caches do you want to clear?</h2>
> [ ] <em>Tempest\Core\DiscoveryCache</em>
  [ ] <em>Tempest\Core\ConfigCache</em>
  [ ] <em>Tempest\View\ViewCache</em>
  [ ] <em>Tempest\Cache\ProjectCache</em>
  [ ] <em>…</em>
```

If you want to clear all caches, you can use the `--all` flag:

```console
./tempest cache:clear --all

<em>Tempest\Core\DiscoveryCache</em> cleared successfully
<em>Tempest\Core\ConfigCache</em> cleared successfully
<em>Tempest\View\ViewCache</em> cleared successfully
<em>Tempest\Cache\ProjectCache</em> cleared successfully
<em>…</em> cleared successfully

<success>Done</success>
```

**It's recommended that you clear all caches on deployment.**

## Enabling or disabling caches

It's likely that you don't want caches enabled in your local environment. Tempest comes with a couple of environment flags to manage whether caching is enabled or not. If you want to enable or disable _all caches_ at once, you can use the `{txt}{:hl-property:CACHE:}` environment variable:

```txt
{:hl-property:CACHE:}={:hl-keyword:true:}
```

If, however, you need more fine-grained control over which caches are enabled or not, you can use the individual environment toggles. Note that, in order for these to work, the `{txt}{:hl-property:CACHE:}` **must be set to `null`**!

```env
{:hl-comment:# The CACHE key is used as a global override to turn all caches on or off:}
{:hl-comment:# Should be true in production, but null or false in local development:}
{:hl-property:CACHE:}={:hl-keyword:null:}

{:hl-comment:# Enable or disable discovery cache:}
{:hl-property:DISCOVERY_CACHE:}={:hl-keyword:false:}

{:hl-comment:# Enable or disable config cache:}
{:hl-property:CONFIG_CACHE:}={:hl-keyword:false:}

{:hl-comment:# Enable or disable view cache:}
{:hl-property:VIEW_CACHE:}={:hl-keyword:false:}

{:hl-comment:# Enable or disable project cache (allround cache):}
{:hl-property:PROJECT_CACHE:}={:hl-keyword:false:}
```

**It's recommended to always set `{txt}{:hl-property:CACHE:}={:hl-keyword:true:}` in production.**

Finally, you can use the `cache:status` command to verify which cashes are enabled and which are not:

```console
./tempest cache:status

<em>Tempest\Core\DiscoveryCache</em> <success>enabled</success>
<em>Tempest\Core\ConfigCache</em> <success>enabled</success>
<em>Tempest\View\ViewCache</em> <error>disabled</error>
<em>Tempest\Cache\ProjectCache</em> <error>disabled</error>
```
