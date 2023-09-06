<p align="center">
  <a href="https://sentry.io?utm_source=github&utm_medium=logo" target="_blank">
    <img src="https://sentry-brand.storage.googleapis.com/sentry-wordmark-dark-280x84.png" alt="Sentry" width="280" height="84">
  </a>
</p>

_Bad software is everywhere, and we're tired of it. Sentry is on a mission to help developers write better software faster, so we can get back to enjoying technology. If you want to join us [<kbd>**Check out our open positions**</kbd>](https://sentry.io/careers/)_

# Official Sentry SDK for Laravel

[![CI](https://github.com/getsentry/sentry-laravel/actions/workflows/ci.yaml/badge.svg)](https://github.com/getsentry/sentry-laravel/actions/workflows/ci.yaml)
[![Latest Stable Version](https://poser.pugx.org/sentry/sentry-laravel/v/stable)](https://packagist.org/packages/sentry/sentry-laravel)
[![License](https://poser.pugx.org/sentry/sentry-laravel/license)](https://packagist.org/packages/sentry/sentry-laravel)
[![Total Downloads](https://poser.pugx.org/sentry/sentry-laravel/downloads)](https://packagist.org/packages/sentry/sentry-laravel)
[![Monthly Downloads](https://poser.pugx.org/sentry/sentry-laravel/d/monthly)](https://packagist.org/packages/sentry/sentry-laravel)
[![Discord](https://img.shields.io/discord/621778831602221064)](https://discord.gg/cWnMQeA)

This is the official Laravel SDK for [Sentry](https://sentry.io).

## Getting Started

The installation steps below work on versions 8.x, 9.x and 10.x of the Laravel framework.

For older Laravel versions and Lumen see:

- [Laravel 8.x & 9.x & 10.x](https://docs.sentry.io/platforms/php/guides/laravel/)
- [Laravel 6.x & 7.x](https://docs.sentry.io/platforms/php/guides/laravel/other-versions/laravel6-7/)
- [Laravel 5.x](https://docs.sentry.io/platforms/php/guides/laravel/other-versions/laravel5/)
- [Laravel 4.x](https://docs.sentry.io/platforms/php/guides/laravel/other-versions/laravel4/)
- [Lumen](https://docs.sentry.io/platforms/php/guides/laravel/other-versions/lumen/)

### Install

Install the `sentry/sentry-laravel` package:

```bash
composer require sentry/sentry-laravel
```

Enable capturing unhandled exception to report to Sentry by making the following change to your `app/Exceptions/Handler.php`:

```php {filename:app/Exceptions/Handler.php}
use Sentry\Laravel\Integration;

public function register(): void
{
    $this->reportable(function (Throwable $e) {
        Integration::captureUnhandledException($e);
    });
}
```

> Alternatively, you can configure Sentry as a [Laravel Log Channel](https://docs.sentry.io/platforms/php/guides/laravel/usage/#log-channels), allowing you to capture `info` and `debug` logs as well.

### Configure

Configure the Sentry DSN with this command:

```shell
php artisan sentry:publish --dsn=___PUBLIC_DSN___
```

It creates the config file (`config/sentry.php`) and adds the `DSN` to your `.env` file.

```shell {filename:.env}
SENTRY_LARAVEL_DSN=___PUBLIC_DSN___
```

### Usage

```php
use function Sentry\captureException;

try {
    $this->functionThatMayFail();
} catch (\Throwable $exception) {
    captureException($exception);
}
```

To learn more about how to use the SDK [refer to our docs](https://docs.sentry.io/platforms/php/guides/laravel/).

## Laravel Version Compatibility

The Laravel and Lumen versions listed below are all currently supported:

- Laravel `>= 10.x.x` on PHP `>= 8.1` is supported starting from `3.2.0`
- Laravel `>= 9.x.x` on PHP `>= 8.0` is supported starting from `2.11.0`
- Laravel `>= 8.x.x` on PHP `>= 7.3` is supported starting from `1.9.0`
- Laravel `>= 7.x.x` on PHP `>= 7.2` is supported starting from `1.7.0`
- Laravel `>= 6.x.x` on PHP `>= 7.2` is supported starting from `1.2.0`

Please note that starting with version `>= 2.0.0` we require PHP Version `>= 7.2` because we are using our new [PHP SDK](https://github.com/getsentry/sentry-php) underneath.

The Laravel versions listed below were supported in previous versions of the Sentry SDK for Laravel:

- Laravel `<= 4.2.x` is supported until `0.8.x`
- Laravel `<= 5.7.x` on PHP `<= 7.0` is supported until `0.11.x`
- Laravel `>= 5.x.x` on PHP `>= 7.1` is supported until `2.14.x`

## Contributing to the SDK

Please refer to [CONTRIBUTING.md](CONTRIBUTING.md).

## Getting Help/Support

If you need help setting up or configuring the Laravel SDK (or anything else in the Sentry universe) please head over to the [Sentry Community on Discord](https://discord.com/invite/Ww9hbqr). There is a ton of great people in our Discord community ready to help you!

## Resources

- [![Documentation](https://img.shields.io/badge/documentation-sentry.io-green.svg)](https://docs.sentry.io/quickstart/)
- [![Discord](https://img.shields.io/discord/621778831602221064)](https://discord.gg/Ww9hbqr)
- [![Stack Overflow](https://img.shields.io/badge/stack%20overflow-sentry-green.svg)](http://stackoverflow.com/questions/tagged/sentry)
- [![Twitter Follow](https://img.shields.io/twitter/follow/getsentry?label=getsentry&style=social)](https://twitter.com/intent/follow?screen_name=getsentry)

## License

Licensed under the MIT license, see [`LICENSE`](LICENSE).
