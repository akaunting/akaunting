# Change Log


All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]

## [2.2.0] - 2020-07-13

### Changed

- Support PHP 7.1-8.0

## [2.1.0] - 2019-12-27

### Changed

- `Http\Client\Exception\NetworkException` no longer extends `Http\Client\Exception\RequestException`,
  in accordance with [PSR-18](https://www.php-fig.org/psr/psr-18/)

## [2.0.0] - 2018-10-31

This version is no BC break for consumers using HTTPlug. However, HTTP clients that
implement HTTPlug need to adjust because we add return type declarations.

### Added

- Support for PSR-18 (HTTP client).

### Changed

- **BC Break:** `HttpClient::sendRequest(RequestInterface $request)` has a return type annotation. The new
signature is `HttpClient::sendRequest(RequestInterface $request): ResponseInterface`.
- **BC Break:** `RequestException::getRequest()` has a return type annotation. The new
signature is `RequestException::getRequest(): RequestInterface`.

### Removed

- PHP 5 support


## [1.1.0] - 2016-08-31

### Added

- HttpFulfilledPromise and HttpRejectedPromise which respect the HttpAsyncClient interface


## [1.0.0] - 2016-01-26

### Removed

- Stability configuration from composer


## [1.0.0-RC1] - 2016-01-12

### Changed

- Updated package files
- Updated promise dependency to RC1


## [1.0.0-beta] - 2015-12-17

### Added

- Puli configuration and binding types

### Changed

- Exception concept


## [1.0.0-alpha3] - 2015-12-13

### Changed

- Async client does not throw exceptions

### Removed

- Promise interface moved to its own repository: [php-http/promise](https://github.com/php-http/promise)


## [1.0.0-alpha2] - 2015-11-16

### Added

- Async client and Promise interface


## [1.0.0-alpha] - 2015-10-26

### Added

- Better domain exceptions.

### Changed

- Purpose of the library: general HTTP CLient abstraction.

### Removed

- Request options: they should be configured at construction time.
- Multiple request sending: should be done asynchronously using Async Client.
- `getName` method


## 0.1.0 - 2015-06-03

### Added

- Initial release


[Unreleased]: https://github.com/php-http/httplug/compare/v2.0.0...HEAD
[2.0.0]: https://github.com/php-http/httplug/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/php-http/httplug/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/php-http/httplug/compare/v1.0.0-RC1...v1.0.0
[1.0.0-RC1]: https://github.com/php-http/httplug/compare/v1.0.0-beta...v1.0.0-RC1
[1.0.0-beta]: https://github.com/php-http/httplug/compare/v1.0.0-alpha3...v1.0.0-beta
[1.0.0-alpha3]: https://github.com/php-http/httplug/compare/v1.0.0-alpha2...v1.0.0-alpha3
[1.0.0-alpha2]: https://github.com/php-http/httplug/compare/v1.0.0-alpha...v1.0.0-alpha2
[1.0.0-alpha]: https://github.com/php-http/httplug/compare/v0.1.0...v1.0.0-alpha
