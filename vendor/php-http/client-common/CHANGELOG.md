# Change Log

## 2.7.0 - 2023-05-17

- Dropped `php-http/message-factory` from composer requirements as it is abandoned and this package does not actually use it.

## 2.6.1 - 2023-04-14

- Allow installation with http-message (PSR-7) version 2 in addition to version 1.
- Support for PHP 8.2

## 2.6.0 - 2022-09-29

- [RedirectPlugin] Redirection of non GET/HEAD requests with a body now removes the body on follow-up requests, if the
  HTTP method changes. To do this, the plugin needs to find a PSR-7 stream implementation. If none is found, you can
  explicitly pass a PSR-17 StreamFactoryInterface in the `stream_factory` option.
  To keep sending the body in all cases, set the `stream_factory` option to null explicitly.

## 2.5.1 - 2022-09-29

### Fixed

- [RedirectPlugin] Fixed handling of redirection to different domain with default port
- [RedirectPlugin] Fixed false positive circular detection in RedirectPlugin in cases when target location does not contain path

## 2.5.0 - 2021-11-26

### Added

- Support for Symfony 6
- Support for PHP 8.1

### Changed

- Dropped support for Symfony 2 and 3 - please keep using version 2.4.0 of this library if you can't update Symfony.

## 2.4.0 - 2021-07-05

### Added

- `strict` option to `RedirectPlugin` to allow preserving the request method on redirections with status 300, 301 and 302.

## 2.3.0 - 2020-07-21

### Fixed

- HttpMethodsClient with PSR RequestFactory
- Bug in the cookie plugin with empty cookies
- Bug when parsing null-valued date headers

### Changed

- Deprecation when constructing a HttpMethodsClient with PSR RequestFactory but without a StreamFactory

## 2.2.1 - 2020-07-13

### Fixed

- Support for PHP 8
- Plugin callable phpdoc

## 2.2.0 - 2020-07-02

### Added

- Plugin client builder for making a `PluginClient`
- Support for the PSR-17 request factory in `HttpMethodsClient`

### Changed

- Restored support for `symfony/options-resolver: ^2.6`
- Consistent implementation of union type checking

### Fixed

- Memory leak when using the `PluginClient` with plugins

## 2.1.0 - 2019-11-18

### Added

- Support Symfony 5

## 2.0.0 - 2019-02-03

### Changed

- HttpClientRouter now throws a HttpClientNoMatchException instead of a RequestException if it can not find a client for the request.
- RetryPlugin will only retry exceptions when there is no response, or a response in the 5xx HTTP code range.
- RetryPlugin also retries when no exception is thrown if the responses has HTTP code in the 5xx range.
  The callbacks for exception handling have been renamed and callbacks for response handling have been added.
- Abstract method `HttpClientPool::chooseHttpClient()` has now an explicit return type (`Http\Client\Common\HttpClientPoolItem`)
- Interface method `Plugin::handleRequest(...)` has now an explicit return type (`Http\Promise\Promise`)
- Made  classes final that are not intended to be extended.
- Added interfaces for BatchClient, HttpClientRouter and HttpMethodsClient.
  (These interfaces use the `Interface` suffix to avoid name collisions.)
- Added an interface for HttpClientPool and moved the abstract class to the HttpClientPool sub namespace.
- AddPathPlugin: Do not add the prefix if the URL already has the same prefix.
- All exceptions in `Http\Client\Common\Exception` are final.

### Removed

- Deprecated option `debug_plugins` has been removed from `PluginClient`
- Deprecated options `decider` and `delay` have been removed from `RetryPlugin`, use `exception_decider` and `exception_delay` instead.

## 1.11.0 - 2021-07-11

### Changed

- Backported from version 2: AddPathPlugin: Do not add the prefix if the URL already has the same prefix.

## 1.10.0 - 2019-11-18

### Added

- Support for Symfony 5

## 1.9.1 - 2019-02-02

### Added

- Updated type hints in doc blocks.

## 1.9.0 - 2019-01-03

### Added

- Support for PSR-18 clients
- Added traits `VersionBridgePlugin` and `VersionBridgeClient` to help plugins and clients to support both
  1.x and 2.x version of `php-http/client-common` and `php-http/httplug`.

### Changed

- RetryPlugin: Renamed the configuration options for the exception retry callback from `decider` to `exception_decider`
  and `delay` to `exception_delay`. The old names still work but are deprecated.

## 1.8.2 - 2018-12-14

### Changed

- When multiple cookies exist, a single header with all cookies is sent as per RFC 6265 Section 5.4
- AddPathPlugin will now trim of ending slashes in paths

## 1.8.1 - 2018-10-09

### Fixed

- Reverted change to RetryPlugin so it again waits when retrying to avoid "can only throw objects" error.

## 1.8.0 - 2018-09-21

### Added

 - Add an option on ErrorPlugin to only throw exception on response with 5XX status code.

### Changed

- AddPathPlugin no longer add prefix multiple times if a request is restarted - it now only adds the prefix if that request chain has not yet passed through the AddPathPlugin
- RetryPlugin no longer wait for retried requests and use a deferred promise instead

### Fixed

- Decoder plugin will now remove header when there is no more encoding, instead of setting to an empty array

## 1.7.0 - 2017-11-30

### Added

- Symfony 4 support

### Changed

- Strict comparison in DecoderPlugin

## 1.6.0 - 2017-10-16

### Added

- Add HttpClientPool client to leverage load balancing and fallback mechanism [see the documentation](http://docs.php-http.org/en/latest/components/client-common.html) for more details.
- `PluginClientFactory` to create `PluginClient` instances.
- Added new option 'delay' for `RetryPlugin`.
- Added new option 'decider' for `RetryPlugin`.
- Supports more cookie date formats in the Cookie Plugin

### Changed

- The `RetryPlugin` does now wait between retries. To disable/change this feature you must write something like:

```php
$plugin = new RetryPlugin(['delay' => function(RequestInterface $request, Exception $e, $retries) {
  return 0;
});
```

### Deprecated

- The `debug_plugins` option for `PluginClient` is deprecated and will be removed in 2.0. Use the decorator design pattern instead like in [ProfilePlugin](https://github.com/php-http/HttplugBundle/blob/de33f9c14252f22093a5ec7d84f17535ab31a384/Collector/ProfilePlugin.php).

## 1.5.0 - 2017-03-30

### Added

- `QueryDefaultsPlugin` to add default query parameters.

## 1.4.2 - 2017-03-18

### Deprecated

- `DecoderPlugin` does not longer claim to support `compress` content encoding

### Fixed

- `CookiePlugin` allows main domain cookies to be sent/stored for subdomains
- `DecoderPlugin` uses the right `FilteredStream` to handle `deflate` content encoding


## 1.4.1 - 2017-02-20

### Fixed

- Cast return value of `StreamInterface::getSize` to string in `ContentLengthPlugin`


## 1.4.0 - 2016-11-04

### Added

- Add Path plugin
- Base URI plugin that combines Add Host and Add Path plugins


## 1.3.0 - 2016-10-16

### Changed

- Fix Emulated Trait to use Http based promise which respect the HttpAsyncClient interface
- Require Httplug 1.1 where we use HTTP specific promises.
- RedirectPlugin: use the full URL instead of the URI to properly keep track of redirects
- Add AddPathPlugin for API URLs with base path
- Add BaseUriPlugin that combines AddHostPlugin and AddPathPlugin


## 1.2.1 - 2016-07-26

### Changed

- AddHostPlugin also sets the port if specified


## 1.2.0 - 2016-07-14

### Added

- Suggest separate plugins in composer.json
- Introduced `debug_plugins` option for `PluginClient`


## 1.1.0 - 2016-05-04

### Added

- Add a flexible http client providing both contract, and only emulating what's necessary
- HTTP Client Router: route requests to underlying clients
- Plugin client and core plugins moved here from `php-http/plugins`

### Deprecated

- Extending client classes, they will be made final in version 2.0


## 1.0.0 - 2016-01-27

### Changed

- Remove useless interface in BatchException


## 0.2.0 - 2016-01-12

### Changed

- Updated package files
- Updated HTTPlug to RC1


## 0.1.1 - 2015-12-26

### Added

- Emulated clients


## 0.1.0 - 2015-12-25

### Added

- Batch client from utils
- Methods client from utils
- Emulators and decorators from client-tools
