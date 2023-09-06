# HTTPlug

[![Latest Version](https://img.shields.io/github/release/php-http/httplug.svg?style=flat-square)](https://github.com/php-http/httplug/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://github.com/php-http/httplug/actions/workflows/ci.yml/badge.svg)](https://github.com/php-http/httplug/actions/workflows/ci.yml)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/php-http/httplug.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/httplug)
[![Quality Score](https://img.shields.io/scrutinizer/g/php-http/httplug.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-http/httplug)
[![Total Downloads](https://img.shields.io/packagist/dt/php-http/httplug.svg?style=flat-square)](https://packagist.org/packages/php-http/httplug)

[![Email](https://img.shields.io/badge/email-team@httplug.io-blue.svg?style=flat-square)](mailto:team@httplug.io)

**HTTPlug, the HTTP client abstraction for PHP.**


## Intro

HTTP client standard built on [PSR-7](http://www.php-fig.org/psr/psr-7/) HTTP
messages. The HttpAsyncClient defines an asynchronous HTTP client for PHP.

This package also provides a synchronous HttpClient interface with the same
method signature as the [PSR-18](http://www.php-fig.org/psr/psr-18/) client.
For synchronous requests, we recommend using PSR-18 directly.


## History

HTTPlug is the official successor of the [ivory http adapter](https://github.com/egeloen/ivory-http-adapter).
HTTPlug is a predecessor of [PSR-18](http://www.php-fig.org/psr/psr-18/)


## Install

Via Composer

``` bash
$ composer require php-http/httplug
```


## Documentation

Please see the [official documentation](http://docs.php-http.org).


## Testing

``` bash
$ composer test
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
