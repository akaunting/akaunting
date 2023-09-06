# Model Caching for Laravel
[![Laravel Package](https://github.com/GeneaLabs/laravel-model-caching/workflows/Laravel%20Package/badge.svg?branch=master)](https://github.com/GeneaLabs/laravel-model-caching/actions?query=workflow%3A%22Laravel+Package%22)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/GeneaLabs/laravel-model-caching/master.svg)](https://scrutinizer-ci.com/g/GeneaLabs/laravel-model-caching)
![BCH Compliance](https://bettercodehub.com/edge/badge/GeneaLabs/laravel-model-caching?branch=master)
[![Coveralls](https://img.shields.io/coveralls/GeneaLabs/laravel-model-caching/master.svg)](https://coveralls.io/github/GeneaLabs/laravel-model-caching)
[![GitHub (pre-)release](https://img.shields.io/github/release/GeneaLabs/laravel-model-caching/all.svg)](https://github.com/GeneaLabs/laravel-model-caching)
[![Packagist](https://img.shields.io/packagist/dt/GeneaLabs/laravel-model-caching.svg)](https://packagist.org/packages/genealabs/laravel-model-caching)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/GeneaLabs/laravel-model-caching/master/LICENSE)

![Model Caching for Laravel masthead image](https://repository-images.githubusercontent.com/103836049/b0d89480-f1b1-11e9-8e13-a7055f008fe6)

## Supporting This Package
This is an MIT-licensed open source project with its ongoing development made possible by the support of the community. If you'd like to support this, and our other packages, please consider [becoming a sponsor](https://github.com/sponsors/mikebronner).

We thank the following sponsors for their generosity, please take a moment to check them out:

- [LIX](https://lix-it.com)

## Impetus
I created this package in response to a client project that had complex, nested
forms with many `<select>`'s that resulted in over 700 database queries on one
page. I needed a package that abstracted the caching process out of the model
for me, and one that would let me cache custom queries, as well as cache model
relationships. This package is an attempt to address those requirements.

## Features
-   automatic, self-invalidating relationship (only eager-loading) caching.
-   automatic, self-invalidating model query caching.
-   automatic use of cache tags for cache providers that support them (will
    flush entire cache for providers that don't).

## Cache Drivers
This package is best suited for taggable cache drivers:
```diff
+ Redis
+ MemCached
+ APC
+ Array
```

It will not work with non-taggable drivers:
```diff
- Database
- File
- DynamoDB
```

## Requirements
- PHP 7.3+
- Laravel 8.0+
    ```diff
    - Please note that prior Laravel versions are not supported and the package
    - versions that are compatible with prior versions of Laravel contain bugs.
    - Please only use with the versions of Laravel noted above to be compatible.
    ```

### Possible Package Conflicts
Any packages that override `newEloquentModel()` from the `Model` class will
likely conflict with this package. Of course, any packages that implement their
own Querybuilder class effectively circumvent this package, rendering them
incompatible.

The following are packages we have identified as conflicting:
- [grimzy/laravel-mysql-spatial](https://github.com/grimzy/laravel-mysql-spatial)
- [fico7489/laravel-pivot](https://github.com/fico7489/laravel-pivot)
- [chelout/laravel-relationship-events](https://github.com/chelout/laravel-relationship-events)
- [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder)
- [dwightwatson/rememberable](https://github.com/dwightwatson/rememberable)
- [kalnoy/nestedset](https://github.com/lazychaser/laravel-nestedset)

### Things That Don't Work Currently
The following items currently do no work with this package:
```diff
- caching of lazy-loaded relationships, see #127. Currently lazy-loaded belongs-to relationships are cached. Caching of other relationships is in the works.
- using select() clauses in Eloquent queries, see #238 (work-around discussed in the issue)
- using transactions. If you are using transactions, you will likely have to manually flush the cache, see [issue #305](https://github.com/GeneaLabs/laravel-model-caching/issues/305).
```

[![installation guide cover](https://user-images.githubusercontent.com/1791050/36356190-fc1982b2-14a2-11e8-85ed-06f8e3b57ae8.png)](https://vimeo.com/256318402)

## Installation
Be sure to not require a specific version of this package when requiring it:
```
composer require genealabs/laravel-model-caching
```

### Gotchas If Using With Lumen
The following steps need to be figured out by you and implemented in your Lumen
app. Googling for ways to do this provided various approaches to this.

1. Register the package to load in Lumen:
    ```php
    $app->register(GeneaLabs\LaravelModelCaching\Providers\Service::class);
    ```
2. Make sure your Lumen app can load config files.
3. Publish this package's config file to the location your app loads config
   files from.

## Upgrade Notes
### 0.6.0
The environment and config variables for disabling this package have changed.
- If you have previously published the config file, publish it again, and adjust as necessary:
  ```sh
  php artisan modelCache:publish --config
  ```

- If you have disabled the package in your .env file, change the entry from `MODEL_CACHE_DISABLED=true` to `MODEL_CACHE_ENABLED=false`.

### 0.5.0
The following implementations have changed (see the respective sections below):
- model-specific cache prefix

## Configuration
### Recommended (Optional) Custom Cache Store
If you would like to use a different cache store than the default one used by
your Laravel application, you may do so by setting the `MODEL_CACHE_STORE`
environment variable in your `.env` file to the name of a cache store configured
in `config/cache.php` (you can define any custom cache store based on your
specific needs there). For example:
```
MODEL_CACHE_STORE=redis2
```

## Usage
For best performance a taggable cache provider is recommended (redis,
memcached). While this is optional, using a non-taggable cache provider will
mean that the entire cache is cleared each time a model is created, saved,
updated, or deleted.

For ease of maintenance, I would recommend adding a `BaseModel` model that
uses `Cachable`, from which all your other models are extended. If you
don't want to do that, simply extend your models directly from `CachedModel`.

Here's an example `BaseModel` class:

```php
<?php namespace App;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;

abstract class BaseModel
{
    use Cachable;
    //
}
```

### Multiple Database Connections
__Thanks to @dtvmedia for suggestion this feature. This is actually a more robust
solution than cache-prefixes.__

Keeping keys separate for multiple database connections is automatically handled.
This is especially important for multi-tenant applications, and of course any
application using multiple database connections.

### Optional Cache Key Prefix
Thanks to @lucian-dragomir for suggesting this feature! You can use cache key
prefixing to keep cache entries separate for multi-tenant applications. For this
it is recommended to add the Cachable trait to a base model, then set the cache
key prefix config value there.

Here's is an example:
```php
<?php namespace GeneaLabs\LaravelModelCaching\Tests\Fixtures;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BaseModel extends Model
{
    use Cachable;

    protected $cachePrefix = "test-prefix";
}
```

The cache prefix can also be set in the configuration to prefix all cached
models across the board:
```php
    'cache-prefix' => 'test-prefix',
```

### Exception: User Model
I would not recommend caching the user model, as it is a special case, since it
extends `Illuminate\Foundation\Auth\User`. Overriding that would break functionality.
Not only that, but it probably isn't a good idea to cache the user model anyway,
since you always want to pull the most up-to-date info on it.

### Experimental: Cache Cool-down In Specific Models
In some instances, you may want to add a cache invalidation cool-down period.
For example you might have a busy site where comments are submitted at a high
rate, and you don't want every comment submission to invalidate the cache. While
I don't necessarily recommend this, you might experiment it's effectiveness.

To use it, it must be enabled in the model (or base model if you want to use it on multiple or all models):
```php
class MyModel extends Model
{
    use Cachable;

    protected $cacheCooldownSeconds = 300; // 5 minutes

    // ...
}
```

After that it can be implemented in queries:
```php
(new Comment)
    ->withCacheCooldownSeconds(30) // override default cooldown seconds in model
    ->get();
```

or:
```php
(new Comment)
    ->withCacheCooldownSeconds() // use default cooldown seconds in model
    ->get();
```

### Disabling Caching of Queries
There are two methods by which model-caching can be disabled:
1. Use `->disableCache()` in a query-by-query instance.
2. Set `MODEL_CACHE_ENABLED=false` in your `.env` file.
3. If you only need to disable the cache for a block of code, or for non-
    eloquent queries, this is probably the better option:
    ```php
    $result = app("model-cache")->runDisabled(function () {
        return (new MyModel)->get(); // or any other stuff you need to run with model-caching disabled
    });
    ```

**Recommendation: use option #1 in all your seeder queries to avoid pulling in
cached information when reseeding multiple times.**
You can disable a given query by using `disableCache()` anywhere in the query chain. For example:
```php
$results = $myModel->disableCache()->where('field', $value)->get();
```

### Manual Flushing of Specific Model
You can flush the cache of a specific model using the following artisan command:
```sh
php artisan modelCache:clear --model=App\Model
```

This comes in handy when manually making updates to the database. You could also
trigger this after making updates to the database from sources outside your
Laravel app.

## Summary
**That's all you need to do. All model queries and relationships are now
cached!**

In testing this has optimized performance on some pages up to 900%! Most often
you should see somewhere around 100% performance increase.

## Commitment to Quality
During package development I try as best as possible to embrace good design and development practices, to help ensure that this package is as good as it can
be. My checklist for package development includes:

-   ✅ Achieve as close to 100% code coverage as possible using unit tests.
-   ✅ Eliminate any issues identified by SensioLabs Insight and Scrutinizer.
-   ✅ Be fully PSR1, PSR2, and PSR4 compliant.
-   ✅ Include comprehensive documentation in README.md.
-   ✅ Provide an up-to-date CHANGELOG.md which adheres to the format outlined
    at <https://keepachangelog.com>.
-   ✅ Have no PHPMD or PHPCS warnings throughout all code.

## Contributing
Please observe and respect all aspects of the included Code of Conduct <https://github.com/GeneaLabs/laravel-model-caching/blob/master/CODE_OF_CONDUCT.md>.

### Reporting Issues
When reporting issues, please fill out the included template as completely as
possible. Incomplete issues may be ignored or closed if there is not enough
information included to be actionable.

### Submitting Pull Requests
Please review the Contribution Guidelines <https://github.com/GeneaLabs/laravel-model-caching/blob/master/CONTRIBUTING.md>.
Only PRs that meet all criterium will be accepted.

## If you ❤️ open-source software, give the repos you use a ⭐️.
We have included the awesome `symfony/thanks` composer package as a dev
dependency. Let your OS package maintainers know you appreciate them by starring
the packages you use. Simply run composer thanks after installing this package.
(And not to worry, since it's a dev-dependency it won't be installed in your
live environment.)
