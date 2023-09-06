# Upgrade to 1.11

doctrine/cache will no longer be maintained and all cache implementations have
been marked as deprecated. These implementations will be removed in 2.0, which
will only contain interfaces to provide a lightweight package for backward
compatibility.

There are two new classes to use in the `Doctrine\Common\Cache\Psr6` namespace:
* The `CacheAdapter` class allows using any Doctrine Cache as PSR-6 cache. This
  is useful to provide a forward compatibility layer in libraries that accept
  Doctrine cache implementations and switch to PSR-6.
* The `DoctrineProvider` class allows using any PSR-6 cache as Doctrine cache.
  This implementation is designed for libraries that leak the cache and want to
  switch to allowing PSR-6 implementations. This class is design to be used
  during the transition phase of sunsetting doctrine/cache support.

A full example to setup a filesystem based PSR-6 cache with symfony/cache
using the `DoctrineProvider` to convert back to Doctrine's `Cache` interface:

```php
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cachePool = new FilesystemAdapter();
$cache = DoctrineProvider::wrap($cachePool);
// $cache instanceof \Doctrine\Common\Cache\Cache
```
