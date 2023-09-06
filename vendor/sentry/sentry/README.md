<p align="center">
  <a href="https://sentry.io/?utm_source=github&utm_medium=logo" target="_blank">
    <img src="https://sentry-brand.storage.googleapis.com/sentry-wordmark-dark-280x84.png" alt="Sentry" width="280" height="84">
  </a>
</p>

_Bad software is everywhere, and we're tired of it. Sentry is on a mission to help developers write better software faster, so we can get back to enjoying technology. If you want to join us [<kbd>**Check out our open positions**</kbd>](https://sentry.io/careers/)_

# Official Sentry SDK for PHP

[![Latest Stable Version](https://poser.pugx.org/sentry/sentry/v/stable)](https://packagist.org/packages/sentry/sentry)
[![License](https://poser.pugx.org/sentry/sentry/license)](https://packagist.org/packages/sentry/sentry)
[![Total Downloads](https://poser.pugx.org/sentry/sentry/downloads)](https://packagist.org/packages/sentry/sentry)
[![Monthly Downloads](https://poser.pugx.org/sentry/sentry/d/monthly)](https://packagist.org/packages/sentry/sentry)
[![Discord](https://img.shields.io/discord/621778831602221064)](https://discord.gg/cWnMQeA)

| Version | Build Status | Code Coverage |
|:---------:|:-------------:|:-----:|
| `master`| [![CI][master Build Status Image]][master Build Status] | [![Coverage Status][master Code Coverage Image]][master Code Coverage] |
| `develop`| [![CI][develop Build Status Image]][develop Build Status] | [![Coverage Status][develop Code Coverage Image]][develop Code Coverage] |

The Sentry PHP error reporter tracks errors and exceptions that happen during the
execution of your application and provides instant notification with detailed
information needed to prioritize, identify, reproduce and fix each issue.

## Getting started

### Install

To install the SDK you will need to be using [Composer]([https://getcomposer.org/)
in your project. To install it please see the [docs](https://getcomposer.org/download/).

This is our "core" SDK, meaning that all the important code regarding error handling lives here.
If you are happy with using the HTTP client we recommend install the SDK like: [`sentry/sdk`](https://github.com/getsentry/sentry-php-sdk)

```bash
composer require sentry/sdk
```

This package (`sentry/sentry`) is not tied to any specific library that sends HTTP messages. Instead,
it uses [Httplug](https://github.com/php-http/httplug) to let users choose whichever
PSR-7 implementation and HTTP client they want to use.

If you just want to get started quickly you should run the following command:

```bash
composer require sentry/sentry php-http/curl-client
```

This is basically what our metapackage (`sentry/sdk`) provides.

This will install the library itself along with an HTTP client adapter that uses
cURL as transport method (provided by Httplug). You do not have to use those
packages if you do not want to. The SDK does not care about which transport method
you want to use because it's an implementation detail of your application. You may
use any package that provides [`php-http/async-client-implementation`](https://packagist.org/providers/php-http/async-client-implementation)
and [`http-message-implementation`](https://packagist.org/providers/psr/http-message-implementation).

### Configuration

```php
\Sentry\init(['dsn' => '___PUBLIC_DSN___' ]);
```

### Usage

```php
try {
    thisFunctionThrows(); // -> throw new \Exception('foo bar');
} catch (\Exception $exception) {
    \Sentry\captureException($exception);
}
```

## Official integrations

The following integrations are fully supported and maintained by the Sentry team.

- [Symfony](https://github.com/getsentry/sentry-symfony)
- [Laravel](https://github.com/getsentry/sentry-laravel)

## 3rd party integrations

The following integrations are available and maintained by members of the Sentry community.

- [Drupal](https://www.drupal.org/project/raven)
- [Neos Flow](https://github.com/flownative/flow-sentry)
- [WordPress](https://wordpress.org/plugins/wp-sentry-integration/)
- [ZendFramework](https://github.com/facile-it/sentry-module)
- [Yii2](https://github.com/notamedia/yii2-sentry)
- [Silverstripe](https://github.com/phptek/silverstripe-sentry)
- [CakePHP 3.0 - 4.3](https://github.com/Connehito/cake-sentry)
- [CakePHP 4.4+](https://github.com/lordsimal/cakephp-sentry)
- ... feel free to be famous, create a port to your favourite platform!

## 3rd party integrations using old SDK 2.x

- [Neos Flow](https://github.com/networkteam/Networkteam.SentryClient)
- [OXID eShop](https://github.com/OXIDprojects/sentry)
- [TYPO3](https://github.com/networkteam/sentry_client)
- [CakePHP](https://github.com/Connehito/cake-sentry/tree/3.x)

## 3rd party integrations using old SDK 1.x

- [Neos CMS](https://github.com/networkteam/Netwokteam.Neos.SentryClient)
- [OpenCart](https://github.com/BurdaPraha/oc_sentry)
- [TYPO3](https://github.com/networkteam/sentry_client/tree/2.1.1)

## Community

- [Documentation](https://docs.sentry.io/error-reporting/quickstart/?platform=php)
- [Bug Tracker](http://github.com/getsentry/sentry-php/issues)
- [Code](http://github.com/getsentry/sentry-php)

## Contributing to the SDK

Please refer to [CONTRIBUTING.md](CONTRIBUTING.md).

## Getting help/support

If you need help setting up or configuring the PHP SDK (or anything else in the Sentry universe) please head over to the [Sentry Community on Discord](https://discord.com/invite/Ww9hbqr). There is a ton of great people in our Discord community ready to help you!

## Resources

- [![Documentation](https://img.shields.io/badge/documentation-sentry.io-green.svg)](https://docs.sentry.io/quickstart/)
- [![Discord](https://img.shields.io/discord/621778831602221064)](https://discord.gg/Ww9hbqr)
- [![Stack Overflow](https://img.shields.io/badge/stack%20overflow-sentry-green.svg)](http://stackoverflow.com/questions/tagged/sentry)
- [![Twitter Follow](https://img.shields.io/twitter/follow/getsentry?label=getsentry&style=social)](https://twitter.com/intent/follow?screen_name=getsentry)

## License

Licensed under the MIT license, see [`LICENSE`](LICENSE)

[master Build Status]: https://github.com/getsentry/sentry-php/actions?query=workflow%3ACI+branch%3Amaster
[master Build Status Image]: https://github.com/getsentry/sentry-php/workflows/CI/badge.svg?branch=master
[develop Build Status]: https://github.com/getsentry/sentry-php/actions?query=workflow%3ACI+branch%3Adevelop
[develop Build Status Image]: https://github.com/getsentry/sentry-php/workflows/CI/badge.svg?branch=develop
[master Code Coverage]: https://codecov.io/gh/getsentry/sentry-php/branch/master
[master Code Coverage Image]: https://img.shields.io/codecov/c/github/getsentry/sentry-php/master?logo=codecov
[develop Code Coverage]: https://codecov.io/gh/getsentry/sentry-php/branch/develop
[develop Code Coverage Image]: https://img.shields.io/codecov/c/github/getsentry/sentry-php/develop?logo=codecov
