# HTTPlug Discovery

[![Latest Version](https://img.shields.io/github/release/php-http/discovery.svg?style=flat-square)](https://github.com/php-http/discovery/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Tests](https://github.com/php-http/discovery/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/php-http/discovery/actions/workflows/ci.yml?query=branch%3Amaster)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/php-http/discovery.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/discovery)
[![Quality Score](https://img.shields.io/scrutinizer/g/php-http/discovery.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/discovery)
[![Total Downloads](https://img.shields.io/packagist/dt/php-http/discovery.svg?style=flat-square)](https://packagist.org/packages/php-http/discovery)

**This library provides auto-discovery and auto-installation of well-known PSR-17, PSR-18 and HTTPlug implementations.**


## Install

Via Composer

``` bash
composer require php-http/discovery
```


## Usage as a library author

Please see the [official documentation](http://php-http.readthedocs.org/en/latest/discovery.html).

If your library/SDK needs a PSR-18 client, here is a quick example.

First, you need to install a PSR-18 client and a PSR-17 factory implementations.
This should be done only for dev dependencies as you don't want to force a
specific implementation on your users:

```bash
composer require --dev symfony/http-client
composer require --dev nyholm/psr7
```

Then, you can disable the Composer plugin embeded in `php-http/discovery`
because you just installed the dev dependencies you need for testing:

```bash
composer config allow-plugins.php-http/discovery false
```

Finally, you need to require `php-http/discovery` and the generic implementations
that your library is going to need:

```bash
composer require 'php-http/discovery:^1.17'
composer require 'psr/http-client-implementation:*'
composer require 'psr/http-factory-implementation:*'
```

Now, you're ready to make an HTTP request:

```php
use Http\Discovery\Psr18Client;

$client = new Psr18Client();

$request = $client->createRequest('GET', 'https://example.com');
$response = $client->sendRequest($request);
```

Internally, this code will use whatever PSR-7, PSR-17 and PSR-18 implementations
that your users have installed.


## Usage as a library user

If you use a library/SDK that requires `php-http/discovery`, you can configure
the auto-discovery mechanism to use a specific implementation when many are
available in your project.

For example, if you have both `nyholm/psr7` and `guzzlehttp/guzzle` in your
project, you can tell `php-http/discovery` to use `guzzlehttp/guzzle` instead of
`nyholm/psr7` by running the following command:

```bash
composer config extra.discovery.psr/http-factory-implementation GuzzleHttp\\Psr7\\HttpFactory
```

This will update your `composer.json` file to add the following configuration:

```json
{
    "extra": {
        "discovery": {
            "psr/http-factory-implementation": "GuzzleHttp\\Psr7\\HttpFactory"
        }
    }
}
```

Don't forget to run `composer install` to apply the changes, and ensure that
the composer plugin is enabled:

```bash
composer config allow-plugins.php-http/discovery true
composer install
```


## Testing

``` bash
composer test
```


## Contributing

Please see our [contributing guide](http://docs.php-http.org/en/latest/development/contributing.html).


## Security

If you discover any security related issues, please contact us at [security@php-http.org](mailto:security@php-http.org).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
