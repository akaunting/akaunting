# PSR-7 implementation

[![Latest Version](https://img.shields.io/github/release/Nyholm/psr7.svg?style=flat-square)](https://github.com/Nyholm/psr7/releases)
[![Total Downloads](https://poser.pugx.org/nyholm/psr7/downloads)](https://packagist.org/packages/nyholm/psr7)
[![Monthly Downloads](https://poser.pugx.org/nyholm/psr7/d/monthly.png)](https://packagist.org/packages/nyholm/psr7)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Static analysis](https://github.com/Nyholm/psr7/actions/workflows/static.yml/badge.svg?branch=master)](https://github.com/Nyholm/psr7/actions/workflows/static.yml?query=branch%3Amaster)
[![Tests](https://github.com/Nyholm/psr7/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/Nyholm/psr7/actions/workflows/tests.yml?query=branch%3Amaster)

A super lightweight PSR-7 implementation. Very strict and very fast.

| Description | Guzzle | Laminas | Slim | Nyholm |
| ---- | ------ | ---- | ---- | ------ |
| Lines of code | 3.300 | 3.100 | 1.900 | 1.000 |
| PSR-7* | 66% | 100% | 75% | 100% |
| PSR-17 | No | Yes | Yes | Yes |
| HTTPlug | No | No | No | Yes |
| Performance (runs per second)** | 14.553 | 14.703 | 13.416 | 17.734 |

\* Percent of completed tests in https://github.com/php-http/psr7-integration-tests

\** Benchmark with 50.000 runs. See https://github.com/devanych/psr-http-benchmark (higher is better)

## Installation

```bash
composer require nyholm/psr7
```

If you are using Symfony Flex then you get all message factories registered as services.

## Usage

The PSR-7 objects do not contain any other public methods than those defined in
the [PSR-7 specification](https://www.php-fig.org/psr/psr-7/).

### Create objects

Use the PSR-17 factory to create requests, streams, URIs etc.

```php
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$request = $psr17Factory->createRequest('GET', 'http://tnyholm.se');
$stream = $psr17Factory->createStream('foobar');
```

### Sending a request

With [HTTPlug](http://httplug.io/) or any other PSR-18 (HTTP client) you may send
requests like:

```bash
composer require kriswallsmith/buzz
```

```php
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$psr18Client = new \Buzz\Client\Curl($psr17Factory);

$request = $psr17Factory->createRequest('GET', 'http://tnyholm.se');
$response = $psr18Client->sendRequest($request);
```

### Create server requests

The [`nyholm/psr7-server`](https://github.com/Nyholm/psr7-server) package can be used
to create server requests from PHP superglobals.

```bash
composer require nyholm/psr7-server
```

```php
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();
```

### Emitting a response

```bash
composer require laminas/laminas-httphandlerrunner
```

```php
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$responseBody = $psr17Factory->createStream('Hello world');
$response = $psr17Factory->createResponse(200)->withBody($responseBody);
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
```

## Our goal

This package is currently maintained by [Tobias Nyholm](http://nyholm.se) and
[Martijn van der Ven](https://vanderven.se/martijn/). They have decided that the
goal of this library should be to provide a super strict implementation of
[PSR-7](https://www.php-fig.org/psr/psr-7/) that is blazing fast.

The package will never include any extra features nor helper methods. All our classes
and functions exist because they are required to fulfill the PSR-7 specification.
