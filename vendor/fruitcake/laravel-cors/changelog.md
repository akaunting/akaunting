# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## v2.0 (2020-05-11)
 
[asm89/stack-cors 2.x](https://github.com/asm89/stack-cors/releases/tag/2.0.0) is now used, with these notable changes:

### Added
- CORS headers are better cachable now, with correct Vary headers (#https://github.com/asm89/stack-cors/pull/70, #https://github.com/asm89/stack-cors/pull/74)

### Changed
- CORS headers are added to non-Origin requests when possible (#https://github.com/asm89/stack-cors/pull/73)
- Requests are no longer blocked by the server, only by the browser (#https://github.com/asm89/stack-cors/pull/70)
 
## v1.0 (2019-12-27)

### Breaking changes
 - Adding the middleware on Route groups is no longer supported. You can use the new `paths` option to match your routes
 - The config file has been changed from `camelCase` to `snake_case`, please update your own config.
 - The deprecated Lumen ServiceProvider has been removed.
 - There is no need to manually configure the `cors` config in Lumen.
 
### Added
 - The `paths` option is added to match certain routes only, while still using global middleware. This allows for better error handling.

## v0.11.0 (2017-12-xx)
### Breaking changes
 - The wildcard matcher is changed. You can use `allowedOriginPatterns` for your own patterns, 
 or simple wildcards in the normal origins. Eg. `*.laravel.com` should still work.

## v0.9.0 (2016-03-2017)
### Breaking changes
 - The `cors` alias is no longer added by default. Use the full class or add the alias yourself.
 - The Lumen ServiceProvider has been removed. Both Laravel and Lumen should use `Barryvdh\Cors\ServiceProvider::class`.
 - `Barryvdh\Cors\Stack\CorsService` moves to `\Barryvdh\Cors\CorsService` (namespace changed).
 - `Barryvdh\Cors@addActualRequestHeaders` will automatically attached when Exception occured.
 
### Added
 - Better error-handling when exceptions occur.
 - A lot of tests, also on older Laravel versions.
