# CORS Middleware for Laravel

[![Build Status][ico-actions]][link-actions]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Implements https://github.com/asm89/stack-cors for Laravel

## About

The `laravel-cors` package allows you to send [Cross-Origin Resource Sharing](http://enable-cors.org/)
headers with Laravel middleware configuration.

If you want to have a global overview of CORS workflow, you can  browse
this [image](http://www.html5rocks.com/static/images/cors_server_flowchart.png).

## Upgrading from 0.x / barryvdh-laravel-cors
When upgrading from 0.x versions, there are some breaking changes:
 - **A new 'paths' property is used to enable/disable CORS on certain routes. This is empty by default, so fill it correctly!**
 - **Group middleware is no longer supported, use the global middleware**
 - The vendor name has changed (see installation/usage)
 - The casing on the props in `cors.php` has changed from camelCase to snake_case, so if you already have a `cors.php` file you will need to update the props in there to match the new casing.

## Features

* Handles CORS pre-flight OPTIONS requests
* Adds CORS headers to your responses
* Match routes to only add CORS to certain Requests

## Installation

Require the `fruitcake/laravel-cors` package in your `composer.json` and update your dependencies:
```sh
composer require fruitcake/laravel-cors
```

If you get a conflict, this could be because an older version of barryvdh/laravel-cors or fruitcake/laravel-cors is installed. Remove the conflicting package first, then try install again:

```sh
composer remove barryvdh/laravel-cors fruitcake/laravel-cors
composer require fruitcake/laravel-cors
```

## Global usage

To allow CORS for all your routes, add the `HandleCors` middleware at the top of the `$middleware` property of  `app/Http/Kernel.php` class:

```php
protected $middleware = [
  \Fruitcake\Cors\HandleCors::class,
    // ...
];
```

Now update the config to define the paths you want to run the CORS service on, (see Configuration below):

```php
'paths' => ['api/*'],
```

## Configuration

The defaults are set in `config/cors.php`. Publish the config to copy the file to your own config:
```sh
php artisan vendor:publish --tag="cors"
```
> **Note:** When using custom headers, like `X-Auth-Token` or `X-Requested-With`, you must set the `allowed_headers` to include those headers. You can also set it to `['*']` to allow all custom headers.

> **Note:** If you are explicitly whitelisting headers, you must include `Origin` or requests will fail to be recognized as CORS.


### Options

| Option                   | Description                                                              | Default value |
|--------------------------|--------------------------------------------------------------------------|---------------|
| paths                    | You can enable CORS for 1 or multiple paths, eg. `['api/*'] `            | `array()`     |
| allowed_origins          | Matches the request origin. Wildcards can be used, eg. `*.mydomain.com`  | `array('*')`  |
| allowed_origins_patterns | Matches the request origin with `preg_match`.                            | `array()`     |
| allowed_methods          | Matches the request method.                                              | `array('*')`  |
| allowed_headers          | Sets the Access-Control-Allow-Headers response header.                   | `array('*')`  |
| exposed_headers          | Sets the Access-Control-Expose-Headers response header.                  | `false`       |
| max_age                  | Sets the Access-Control-Max-Age response header.                         | `0`           |
| supports_credentials     | Sets the Access-Control-Allow-Credentials header.                        | `false`       |


`allowed_origins`, `allowed_headers` and `allowed_methods` can be set to `['*']` to accept any value.

> **Note:** For `allowed_origins` you must include the scheme when not using a wildcard, eg. `['http://example.com', 'https://example.com']`. You must also take into account that the scheme will be present when using `allowed_origins_patterns`.

> **Note:** Try to be a specific as possible. You can start developing with loose constraints, but it's better to be as strict as possible!

> **Note:** Because of [http method overriding](http://symfony.com/doc/current/reference/configuration/framework.html#http-method-override) in Laravel, allowing POST methods will also enable the API users to perform PUT and DELETE requests as well.

### Lumen

On Lumen, just register the ServiceProvider manually in your `bootstrap/app.php` file:

```php
$app->register(Fruitcake\Cors\CorsServiceProvider::class);
```

Also copy the [cors.php](https://github.com/fruitcake/laravel-cors/blob/master/config/cors.php) config file to `config/cors.php` and put it into action:

```php
$app->configure('cors');
```

## Global usage for Lumen

To allow CORS for all your routes, add the `HandleCors` middleware to the global middleware and set the `paths` property in the config.

```php
$app->middleware([
    // ...
    Fruitcake\Cors\HandleCors::class,
]);
```

## Common problems

### Error handling, Middleware order

Sometimes errors/middleware that return own responses can prevent the CORS Middleware from being run. Try changing the order of the Middleware and make sure it's the first entry in the global middleware, not a route group. Also check your logs for actual errors, because without CORS, the errors will be swallowed by the browser, only showing CORS errors.

### Authorization headers / Credentials

If your Request includes an Authorization header or uses Credentials mode, set the `supports_credentials` value in the config to true. This will set the [Access-Control-Allow-Credentials](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials) Header to `true`.

### Echo/die

If you `echo()`, `dd()`, `die()`, `exit()`, `dump()` etc in your code, you will break the Middleware flow. When output is sent before headers, CORS cannot be added. When the scripts exits before the CORS middleware finished, CORS headers will not be added. Always return a proper response or throw an Exception.

### Disabling CSRF protection for your API

If possible, use a route group with CSRF protection disabled.
Otherwise you can disable CSRF for certain requests in `App\Http\Middleware\VerifyCsrfToken`:

```php
protected $except = [
    'api/*'
];
```


## License

Released under the MIT License, see [LICENSE](LICENSE).

[ico-version]: https://img.shields.io/packagist/v/fruitcake/laravel-cors.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-actions]: https://github.com/fruitcake/laravel-cors/workflows/.github/workflows/run-tests.yml/badge.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/fruitcake/laravel-cors.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/fruitcake/laravel-cors.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/fruitcake/laravel-cors.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/fruitcake/laravel-cors
[link-actions]: https://github.com/fruitcake/laravel-cors/actions
[link-scrutinizer]: https://scrutinizer-ci.com/g/fruitcake/laravel-cors/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/fruitcake/laravel-cors
[link-downloads]: https://packagist.org/packages/fruitcake/laravel-cors
[link-author]: https://github.com/fruitcake
[link-contributors]: ../../contributors
