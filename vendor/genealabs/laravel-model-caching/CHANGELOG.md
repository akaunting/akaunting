# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [0.12.5] - 2022-07-30
### Added
- pivotSynced event listener to flush cache when performing BelongsToMany::sync

[...]

## [0.10.3] - 2021-03-16
### Changed
- The from part of the query ($query->from) instead of the table name of the model ($model->table)
  is now used for determining the table part caching key fragment

## [0.10.2] - 2020-09-04
### Added
- functionality to inject custom builder class for handling conflicting packages.

## [0.10.1] - 2020-08-02
### Fixed
- typos in test class.

## [0.10.0] - 2020-08-02
### Removed
- PREDIS dependency. Going forward testing will be done against PHPREDIS, as
    that is installed by default on Laravel Forge servers and the officially
    recommended Redis client, since PREDIS is no longer maintained.

## [0.9.0] - 2020-07-17
### Removed
- support for PHP 7.2 due to incompatibility.

## [0.8.10] - 2020-07-08
### Fixed
- update and insert methods when called on cached relations.

## [0.8.9] - 2020-07-03
### Added
- changes meant for 0.8.8 that were inadvertently not committed.

## [0.8.8] - 2020-07-02
### Fixed
- return type of `applyScopes` to match parent class.

## [0.8.7] - 2020-07-02
### Added
- check for scopes to avoid them being applied twice. Thanks @saernz!

## [0.8.6] - 2020-05-12
### Changed
- implementation of `debug_backtrace()` to reduce memory consumption. Thanks @saernz!

## [0.8.5] - 2020-05-02
### Changed
- PHP dependency version to ">=7.2.5" to be more inline with Laravel.

## [0.8.4] - 2020-04-29
### Added
- additional unit tests.

### Updated
- key generation to be more explicit in details with global scopes. Thanks @Drewdan!

## [0.8.3] - 2020-04-15
### Added
- initial tests for Nova integration. Thanks @dmason30!

### Fixed
- travis build process, tests on travis are now back to green!!!

## [0.8.2] - 2020-04-13
### Fixed
- issue with incorrectly adding to currentBinding if item was not in query
  binding. Thanks @irvine1231!

## [0.8.1] - 2020-04-09
### Fixed
- `jsonContains()` with array values. Thanks @dmason30!
- `truncate()` to flush cache. Thanks @dmason30!

## [0.8.0] - 2020-02-29
### Added
- Laravel 7 compatibility.

## [0.7.4] - 2019-12-22
### Added
- documentation to explain that database transactions are currently not supported.
- compatibility with binary UUIDs. Thanks @padre!

### Changed
- detection if cache is enabled in the `$model->all()` method. Thanks @titrxw!

## [0.7.2] - 2019-10-12
### Fixed
- improper caching of non-cachable eagerloaded relationships. Now any query with non-cachable eagerloaded relationships will not be cached in its entirety to prevent stale data. Special thanks to @Hornet-Wing for his help in this.

## [0.7.1] - 2019-10-02
### Fixed
- dependency version constraints.
### Added
- various tests.

## [0.7.0] - 2019-08-28
### Added
- Laravel 6.0 support.

## [0.6.3] - 2019-08-27
### Fixed
- caching of methods that could pass field names as string or array.

## [0.6.2] - 2019-07-26
### Added
- 'modelCache:publish' artisan command.
- Spatie's QueryBuilder package to list of incompatible packages.

### Fixed
- registration of config file in service provider.
- detection if cache is disabled.
- flushing of cache for pivot events.

## [0.6.1] - 2019-07-20
### Added
- config and environment variable to allow removal of database information from cache-key.

## [0.6.0] - 2019-07-20
### Changed
- environment variable `MODEL_CACHE_DISABLED` to `MODEL_CACHE_ENABLED` to better conform to standards.

### Fixed
- how cache key is constructed for SQLite.

## [0.5.6] - 2019-06-20
### Removed
- all use of helper methods to allow non-Laravel apps to use this package:
  - app()
  - config()
  - cache()
  - db()
  - now()
  - request()

## [0.5.5] - 2019-05-27
### Fixed
- parsing of soft-deleted-related queries:
  - withTrashed()
  - onlyTrashed()
  - withoutTrashed()

## [0.5.4] - 2019-05-27
### Fixed
- parsing of global scopes. Rewrote how global scopes are analyzed to create appropriate cache key.

## [0.5.3] - 2019-05-21
### Fixed
- calling `flushCache()` on non-cachable related models.

## [0.5.2] - 21 May 2019
Pushed changes intended for 0.5.1. Forgot to push changes to repo. 👀

## [0.5.1] - 21 May 2019
### Fixed
- caching of where clauses using DateTime instead of Carbon.

## [0.5.0] - 20 May 2019
### Changed
- implementation of model-based cache prefix.
- the way tests are run to be MUCH more performant.

### Removed
- dead code.

## [0.4.24] - 18 May 2019
### Fixed
- BelongsToMany relationship to not cache if the target model is not also cachable.

## [0.4.23] - 18 May 2019
### Added
- tests for lazy-loading the following relationships:
    - BelongsTo
    - BelongsToMany
    - HasMany
    - HasOne

### Fixed
- BelongsToMany relationship cache not being automatically invalidated.

## [0.4.22] - 17 May 2019
### Fixed
- issue introduce in previous release related to cache cooldown and prefixes.

## [0.4.21] - 17 May 2019
### Added
- my own implementation of laravel pivot events, based on `fico7489/laravel-pivot`.

### Fixed
- Laravel Telescope compatibility.

### Removed
- dependency on `fico7489/laravel-pivot`.

## [0.4.20] - 17 May 2019
### Added
- caching lazy-loading of belongs-to relationships. Thanks @tmishutin for leading the way forward on this effort! Hopefully this solution will work as a template for lazy-loading other relationship types going forward.

### Fixed
- an issue with prefixing found during testing.

### Removed
- unused code.

## [0.4.19] - 16 May 2019
### Added
- work-around for Laravel Telescope compatibility to README.

## [0.4.18] - 14 May 2019
### Fixed
- polymorphic relationship caching, as well as other queries using `InRaw`.

## [0.4.17] - 12 May 2019
### Fixed
- generation of cache key where clauses.

## [0.4.16] - 10 May 2019
### Changed
- the way the database name is determined when creating the cache prefix.

## [0.4.15] - 9 May 2019
### Fixed
- bindings used in `whereIn` clauses.

## [0.4.14] - 21 Apr 2019
### Fixed
- where `first()` didn't pass an array parameter.

## [0.4.13] - 4 Apr 2019
### Added
- helper function to run closure with model-caching disabled. Thanks for the suggestion, @mycarrysun

## [0.4.12] - 3 Apr 2019
### Updated
- string and array helpers to use the `Str` and `Arr` classes directly, in preparation for helper deprecations in Laravel 5.9. Thanks @mycarrysun

### Fixed
- disabling of model caching on relationship queries if model caching was disabled on the model. Thanks @mycarrysun
- error that occurred if `whereIn` was given an empty array. Thanks @Ben52

## [0.4.11] - 25 Mar 2019
### Changed
- `useCacheCooldown` to `cacheCooldownSeconds` in models.

## [0.4.10] - 24 Mar 2019
### Updated
- cache cool down functionality to not trigger if it is not set on the model. This should hopefully improve performance. Thanks @mycarrysun for implementing the PR, and thanks @yemenifree for alerting me to the issue!

## [0.4.9] - 6 Mar 2019
### Changed
- `laravel-pivot` dependency back to that of the original owner, as Laravel 5.8 compatibility has been restored.

## [0.4.8] - 4 Mar 2019
### Changed
- to rely on temporarily published `mikebronner/laravel-pivot` package on packagist.

## [0.4.7] - 1 Mar 2019
### Fixed
- installation of patched laravel-pivot dependency.

## [0.4.6] - 28 Feb 2019
### Fixed
- dependency constraints from 5.8 to 5.8.*.

## [0.4.5] - 28 Feb 2019
### Fixed
- using `find()` to get multiple items via an array. Thanks @cluebattery !

## [0.4.4] - 28 Feb 2019
### Added
- functionality for caching of model relationships across different connections and databases. Thanks @PokeGuys for starting the conversation around this problem.

## [0.4.3] - 28 Feb 2019
### Fixed
- cache cooldown flush when cool-down seconds option was used.

## [0.4.2] - 28 Feb 2019
### Fixed
- `laravel-pivot` package compatibility temporarily with Laravel 5.8 patch of my own until they provide compatibility.

## [0.4.1] - 28 Feb 2019
### Fixed
- version requirements in composer.json.

## [0.4.0] - 28 Feb 2019
### Added
- Laravel 5.8 compatibility.

### Removed
- compatibility with previous versions of Laravel, as it was no longer sustainable with all the changes required.

## [0.3.7] - 6 Feb 2019
### Updated
- depency laravel-pivot to next major release version, from a dev-version.

### Changed
- reference to `request()` helper to `app("request")` for Lumen compatibility. Thanks @PokeGuys

## [0.3.6] - 8 Dec 2018
### Added
- functionality to invalidate cache after running `increment()` and `decrement()` queries.

## [0.3.5] - 30 Nov 2018
### Added
- tracking of model table in cache key for those using dynamic table names in models.

### Updated
- dependency of `laravel-pivot` package to use the new code branch which includes a fix for Laravel Telescope compatibility.

## [0.3.5] - 28 Nov 2018
### Fixed
- relationship queries breaking on new where clause type `InRaw`.

## [0.3.3] - 10 Nov 2018
### Fixed
- typo in method `checkCooldownAndFlushAfterPersiting()` to
  `checkCooldownAndFlushAfterPersisting()`; thanks @jacobzlogar!

## [0.3.2] - 3 Nov 2018
### Added
- handling of `whereJsonContains()` and `orWhereJsonContains()`.

### Fixed
- price field value generation in BookFactory to not exceed database field limits.

## [0.3.1] - 7 Oct 2018
### Changed
- use of `cache()` helper method to `app("cache")` to allow for better Lumen compatibility. Thanks @nope7777!

### Updated
- test script for Laravel 5.7 to use non-dev version of test dependency.

### Removed
- `codedungeon/phpunit-result-printer` unit test output printer.

### Fixed
- use of custom pagination name.
- edge-case where tag creation failed.
- usage of `forceDelete()` in the Builder.

## [0.3.0] - 10 Sep 2018
### Added
- Laravel 5.7 compatibility.

## [0.2.64] - 25 Jul 2018
### Fixed
- caching of subqueries of `->whereNotIn()` and `->whereIn()`.
- nested where bindings.

## [0.2.63] - 9 Jun 2018
### Fixed
- where clause binding resolution issue.

## [0.2.62] - 1 Jun 2018
### Fixed
- function name typo.

### Removed
- dump() used for debugging.

## [0.2.61] - 31 May 2018
### Fixed
- caching of paginated queries with page identifiers as arrays (`?page[size]=1`).

## [0.2.60] - 27 May 2018
### Added
- unit tests for multiple versions of Laravel simultaneously.
- backwards-compatibility to Laravel 5.4.

## [0.2.59] - 27 May 2018
### Fixed
- caching of queries with `whereNotIn` clauses.

### Updated
- readme to specify that lazy-loaded relationships are currently not cached.

## [0.2.58] - 24 May 2018
### Fixed
- caching of queries with `whereIn` clauses.

## [0.2.57] - 19 May 2018
### Added
- database name to cache keys and tags to help with multi-tenancy use-cases.

### Fixed
- `find()` using array parameter.

## [0.2.56] - 12 May 2018
### Fixed
- nested `whereNull` within `whereHas`.

## [0.2.55] - 6 May 2018
### Fixed
- caching of `between` where clauses.
- test cache keys and brought them back to green.

## [0.2.54] - 6 May 2018
### Fixed
- caching of query parameter bindings.

## [0.2.53] - 6 May 2018
### Fixed
- `->inRandomOrder()` to not cache the query.

## [0.2.52] - 21 Mar 2018
### Changed
- `flush` console command to be called `clear`, to match other laravel commands.

### Fixed
- implementation of `count()` method.

## [0.2.51] - 10 Mar 2018
### Added
- disabling of `all()` query.

## [0.2.50] - 10 Mar 2018
### Added
- cache invalidation when `destroy()`ing models.

### Fixed
- cache tag generation when calling `all()` queries that prevented proper
  cache invalidation.

## [0.2.49] - 9 Mar 2018
### Fixed
- caching of `->first()` queries.

## [0.2.48] - 9 Mar 2018
### Added
- database connection name to cache prefix.

## [0.2.47] - 5 Mar 2018
### Fixed
- exception when calling disableCache() when caching is already disabled via config.

## [0.2.46] - 5 Mar 2018
### Fixed
- package dependency version to work with Laravel 5.5.

## [0.2.45] - 3 Mar 2018
### Fixed
- pagination cache key generation; fixes #85.

## [0.2.44] - 3 Mar 2018
### Fixed
- disabling of caching using the query scope.

## [0.2.43] - 2 Mar 2018
### Fixed
- actions on belongsToMany relationships not flushing cache when needed.

## [0.2.42] - 28 Feb 2018
### Added
- additional integration tests for additional use cases.

### Fixed
- flushing a specific model from the command line that extended a base class and did not use the trait directly.

## [0.2.41] - 26 Feb 2018
### Fixes
- cache invalidation when using ->insert() method.
- cache invalidation when using ->update() method.

## [0.2.40] - 24 Feb 2018
### Updated
- code with some home-cleaning and refactoring.

## [0.2.39] - 24 Feb 2018
### Updated
- CachedBuilder class with some refactoring and cleanup.

## [0.2.38] - 24 Feb 2018
### Added
- cache-invalidation-cool-down functionality.

## [0.2.37] - 23 Feb 2018
### Added
- disabling of `->all()` method caching via config flag.

## [0.2.36] - 23 Feb 2018
### Added
- config setting to allow disabling of model-caching.

## [0.2.35] - 21 Feb 2018
### Fixed
- cache key generation for `find()` and `findOrFail()`.

### Added
- caching for `paginate()`;

## [0.2.34] - 21 Feb 2018
### Added
- implementation tests using redis.
- additional tests for some edge case scenarios.

### Fixed
- cache key prefix functionality.

### Updated
- tests through refactoring and cleaning up.

## [0.2.33] - 19 Feb 2018
### Added
- unit test to make sure `Model::all()` returns a collection when only only
  record is retrieved.
- console command to flush entire model-cache.

## [0.2.32] - 19 Feb 2018
### Fixed
- hash collision logic to not run query twice if not needed.

## [0.2.31] - 18 Feb 2018
### Added
- optional cache key prefixing.

## [0.2.30] - 18 Feb 2018
### Changed
- detection of Cachable trait to use `class_uses()` instead of looking for
  method.

## [0.2.29] - 18 Feb 2018
### Added
- hash collision detection and prevetion.

## [0.2.28] - 18 Feb 2018
### Changed
- disabling of cache from using session to use cache-key instead.

## [0.2.27] - 17 Feb 2018
### Fixed
- the erroneous use of `arrayEmpty()` function, changed to simple `count()`.

## [0.2.26] - 16 Feb 2018
### Added
- refactor functionality to trait (thanks @rs-sliske!).

## [0.2.25] - 16 Feb 2018
### Fixed
- readme spelling errors (thanks @fridzema!).

## [0.2.24] - 16 Feb 2018
### Fixed
- whereNotIn query caching.

## [0.2.23] - 13 Feb 2018
### Fixed
- whereBetween and value bindings parsing.

## [0.2.22] - 10 Feb 2018
### Fixed
- Laravel 5.5 dependencies.

## [0.2.21] - 9 Feb 2018
### Added
- Laravel 5.6 compatibility.

## [0.2.20] - 7 Feb 2018
### Fixed
- previously existing unit tests to properly consider changes made in 0.2.19.

## [0.2.19] - 7 Feb 2018
### Fixed
- parsing of where clause operators.

## [0.2.18] - 16 Jan 2018
### Added
- hashing of cache keys to prevent key length over-run issues.

### Updated
- dependency version constraint for "pretty test printer".

## [0.2.17] - 10 Jan 2018
###Added
- caching for value() querybuilder method.

### Updated
- tests to use Orchestral Testbench.

## [0.2.16] - 5 Jan 2018
### Added
- `thanks` package.

### Updated
- readme explaining `thanks` package.

## [0.2.15] - 30 Dec 2017
### Added
- sanity checks for artisan command with feedback as to what needs to be fixed.

## [0.2.14] - 30 Dec 2017
### Added
- ability to flush cache for a given model via Artisan command.

## [0.2.13] - 28 Dec 2017
### Added
- ability to define custom cache store in `.env` file.

## [0.2.12] - 14 Dec 2017
### Added
- chainable method to disable caching of queries.

## [0.2.11] - 13 Dec 2017
### Added
- functionality to clear corresponding cache tags when model is deleted.

## [0.2.10] - 5 Dec 2017
### Fixed
- caching when using `orderByRaw()`.

## [0.2.9] - 19 Nov 2017
### Added
- test for query scopes.
- test for relationship query.

### Updated
- readme file.
- travis configuration.

## [0.2.8] - 2017-10-17
### Updated
- code with optimizations and refactoring.

## [0.2.7] - 2017-10-16
### Added
- remaining unit tests that were incomplete, thanks everyone who participated!
- added parsing of where `doesnthave()` condition.

## [0.2.6] - 2017-10-12
### Added
- orderBy clause to cache key. Thanks @RobMKR for the PR!

## [0.2.5] - 2017-10-04
### Fixed
- parsing of nested, exists, raw, and column where clauses.

## [0.2.4] - 2017-10-03
### Added
- .gitignore file to reduce download size for production environment.

### Fixed
- parsing of where clauses with null and notnull.

## [0.2.3] - 2017-10-03
### Fixed
- parsing of where clauses to account for some edge cases.

## [0.2.2] - 2017-09-29
### Added
- additional unit tests for checking caching of lazy-loaded relationships.

### Fixed
- generation of cache key for queries with where clauses.

## [0.2.1] - 2017-09-25
### Fixed
- where clause parsing when where clause is empty.

## [0.2.0] - 2017-09-24
### Changed
- approach to caching things. Completely rewrote the CachedBuilder class.

### Added
- many, many more tests.

## [0.1.0] - 2017-09-22
### Added
- initial development of package.
- unit tests with 100% code coverage.
