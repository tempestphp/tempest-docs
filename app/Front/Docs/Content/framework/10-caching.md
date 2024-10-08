---
title: Caching
---

Tempest comes with a simple wrapper around PSR-6, meaning you can use all PSR-6 compliant cache adapters. Tempest uses [Symfony's Cache Component](https://symfony.com/doc/current/components/cache.html) as a default implementation. 

## Configuration

Tempest will use file caching by default. You can however configure another adapter via `CacheConfig`:

```php
// Config/cache.php

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

Note that `CacheConfig` is used for the default cache. You can create your own caches with different adapters like so:

```php
use Tempest\Cache\Cache;
use Tempest\Cache\IsCache;
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

## Caching stuff

In order to cache stuff, you only need to inject the `Cache` interface (or your custom implementations), and you're ready to go:

```php
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
            expiresAt: (new DateTimeImmutable())->add(new DateInterval('P1D'))
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

## Clearing caches

Tempest comes with a `cache:clear` command built-in which allows you to pick which caches you want to clear:

```console
./tempest cache:clear

<h2>Which caches do you want to clear?</h2> 
> [ ] <em>Tempest\Cache\GenericCache</em>
> [ ] <em>…</em>
```

If you want to clear all caches, you can use the `--all` flag:

```console
./tempest cache:clear --all

<em>Tempest\Cache\GenericCache</em> cleared successfully
<em>…</em> cleared successfully

<success>Done</success> 
```