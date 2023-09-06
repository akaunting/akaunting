# Change Log

## 1.19.1 - 2023-07-11

- [#250](https://github.com/php-http/discovery/pull/250) - Fix: Buzz client instantiation using deprecated Message Factory Discovery, use PSR-17 factory discovery instead.

## 1.19.0 - 2023-06-19

- [#249](https://github.com/php-http/discovery/pull/249) - Have composer plugin correctly install Symfony http client when nothing explicitly requires psr 18 resp. httplug.
- [#241](https://github.com/php-http/discovery/pull/241) - Support discovering PSR-17 factories of `httpsoft/http-message` package

## 1.18.1 - 2023-05-17

- [#242](https://github.com/php-http/discovery/pull/242) - Better exception message when no legacy php-http message factories can be built. Also needs php-http/message-factory package and they are deprecated in favor of PSR-17 anyways.

## 1.18.0 - 2023-05-03

- [#235](https://github.com/php-http/discovery/pull/235) - Deprecate HttpClientDiscovery, use Psr18ClientDiscovery instead
- [#238](https://github.com/php-http/discovery/pull/238) - Skip requiring php-http/message-factory when installing symfony/http-client 6.3+
- [#239](https://github.com/php-http/discovery/pull/239) - Skip auto-installing when the root package's extra.discovery is enough

## 1.17.0 - 2023-04-26

- [#230](https://github.com/php-http/discovery/pull/230) - Add Psr18Client to make it straightforward to use PSR-18
- [#232](https://github.com/php-http/discovery/pull/232) - Allow pinning the preferred implementations in composer.json
- [#233](https://github.com/php-http/discovery/pull/233) - Fix Psr17Factory::createServerRequestFromGlobals() when uploaded files have been moved

## 1.16.0 - 2023-04-26

- [#225](https://github.com/php-http/discovery/pull/225) - Remove support for the abandoned Zend Diactoros which has been replaced with Laminas Diactoros; marked the zend library as conflict in composer.json to avoid confusion
- [#227](https://github.com/php-http/discovery/pull/227) - Fix handling requests with nested files

## 1.15.3 - 2023-03-31

- [#224](https://github.com/php-http/discovery/pull/224) - Fix regression with Magento classloader

## 1.15.2 - 2023-02-11

- [#219](https://github.com/php-http/discovery/pull/219) - Fix handling of replaced packages

## 1.15.1 - 2023-02-10

- [#214](https://github.com/php-http/discovery/pull/214) - Fix resolving deps for psr/http-message-implementation
- [#216](https://github.com/php-http/discovery/pull/216) - Fix keeping platform requirements when rebooting composer
- [#217](https://github.com/php-http/discovery/pull/217) - Set extra.plugin-optional composer flag

## 1.15.0 - 2023-02-09

- [#209](https://github.com/php-http/discovery/pull/209) - Add generic `Psr17Factory` class
- [#208](https://github.com/php-http/discovery/pull/208) - Add composer plugin to auto-install missing implementations.
  When libraries require an http implementation but no packages providing that implementation is installed in the application, the plugin will automatically install one.
  This is only done for libraries that directly require php-http/discovery to avoid unexpected dependency installation.

## 1.14.3 - 2022-07-11

- [#207](https://github.com/php-http/discovery/pull/207) - Updates Exception to extend Throwable solving static analysis errors for consumers

## 1.14.2 - 2022-05-25

- [#202](https://github.com/php-http/discovery/pull/202) - Avoid error when the Symfony PSR-18 client exists but its dependencies are not installed

## 1.14.1 - 2021-09-18

- [#199](https://github.com/php-http/discovery/pull/199) - Fixes message factory discovery for `laminas-diactoros ^2.7`

## 1.14.0 - 2021-06-21

- Deprecate puli as it has been unmaintained for a long time and is not compatible with composer 2 https://github.com/php-http/discovery/pull/195

## 1.13.0 - 2020-11-27

- Support discovering PSR-17 factories of `slim/psr7` package https://github.com/php-http/discovery/pull/192

## 1.12.0 - 2020-09-22

- Support discovering HttpClient of `php-http/guzzle7-adapter` https://github.com/php-http/discovery/pull/189

## 1.11.0 - 2020-09-22

- Use correct method name to find Uri Factory in PSR17 https://github.com/php-http/discovery/pull/181

## 1.10.0 - 2020-09-04

- Discover PSR-18 implementation of phalcon

## 1.9.1 - 2020-07-13

### Fixed

- Support PHP 7.4 and 8.0

## 1.9.0 - 2020-07-02

### Added

- Support discovering PSR-18 factories of `guzzlehttp/guzzle` 7+

## 1.8.0 - 2020-06-14

### Added

- Support discovering PSR-17 factories of `guzzlehttp/psr7` package
- Support discovering PSR-17 factories of `laminas/laminas-diactoros` package
- `ClassDiscovery::getStrategies()` to retrieve the list of current strategies.

### Fixed

- Ignore exception during discovery when Symfony HttplugClient checks if HTTPlug is available.

## 1.7.4 - 2020-01-03

### Fixed

- Improve conditions on Symfony's async HTTPlug client.

## 1.7.3 - 2019-12-27

### Fixed

- Enough conditions to only use Symfony HTTP client if all needed components are available.

## 1.7.2 - 2019-12-27

### Fixed

- Allow a condition to specify an interface and not just classes.

## 1.7.1 - 2019-12-26

### Fixed

- Better conditions to see if Symfony's HTTP clients are available.

## 1.7.0 - 2019-06-30

### Added

- Dropped support for PHP < 7.1
- Support for `symfony/http-client`

## 1.6.1 - 2019-02-23

### Fixed

- MockClientStrategy also provides the mock client when requesting an async client

## 1.6.0 - 2019-01-23

### Added

- Support for PSR-17 factories
- Support for PSR-18 clients

## 1.5.2 - 2018-12-31

Corrected mistakes in 1.5.1. The different between 1.5.2 and 1.5.0 is that
we removed some PHP 7 code.

https://github.com/php-http/discovery/compare/1.5.0...1.5.2

## 1.5.1 - 2018-12-31

This version added new features by mistake. These are reverted in 1.5.2.

Do not use 1.5.1.

### Fixed

- Removed PHP 7 code

## 1.5.0 - 2018-12-30

### Added

- Support for `nyholm/psr7` version 1.0.
- `ClassDiscovery::safeClassExists` which will help Magento users.
- Support for HTTPlug 2.0
- Support for Buzz 1.0
- Better error message when nothing found by introducing a new exception: `NoCandidateFoundException`.

### Fixed

- Fixed condition evaluation, it should stop after first invalid condition.

## 1.4.0 - 2018-02-06

### Added

- Discovery support for nyholm/psr7

## 1.3.0 - 2017-08-03

### Added

- Discovery support for CakePHP adapter
- Discovery support for Zend adapter
- Discovery support for Artax adapter

## 1.2.1 - 2017-03-02

### Fixed

- Fixed minor issue with `MockClientStrategy`, also added more tests.

## 1.2.0 - 2017-02-12

### Added

- MockClientStrategy class.

## 1.1.1 - 2016-11-27

### Changed

- Made exception messages clearer. `StrategyUnavailableException` is no longer the previous exception to `DiscoveryFailedException`.
- `CommonClassesStrategy` is using `self` instead of `static`. Using `static` makes no sense when `CommonClassesStrategy` is final.

## 1.1.0 - 2016-10-20

### Added

- Discovery support for Slim Framework factories

## 1.0.0 - 2016-07-18

### Added

- Added back `Http\Discovery\NotFoundException` to preserve BC with 0.8 version. You may upgrade from 0.8.x and 0.9.x to 1.0.0 without any BC breaks.
- Added interface `Http\Discovery\Exception` which is implemented by all our exceptions

### Changed

- Puli strategy renamed to Puli Beta strategy to prevent incompatibility with a future Puli stable

### Deprecated

- For BC reasons, the old `Http\Discovery\NotFoundException` (extending the new exception) will be thrown until version 2.0


## 0.9.1 - 2016-06-28

### Changed

- Dropping PHP 5.4 support because we use the ::class constant.


## 0.9.0 - 2016-06-25

### Added

- Discovery strategies to find classes

### Changed

- [Puli](http://puli.io) made optional
- Improved exceptions
- **[BC] `NotFoundException` moved to `Http\Discovery\Exception\NotFoundException`**


## 0.8.0 - 2016-02-11

### Changed

- Puli composer plugin must be installed separately


## 0.7.0 - 2016-01-15

### Added

- Temporary puli.phar (Beta 10) executable

### Changed

- Updated HTTPlug dependencies
- Updated Puli dependencies
- Local configuration to make tests passing

### Removed

- Puli CLI dependency


## 0.6.4 - 2016-01-07

### Fixed

- Puli [not working](https://twitter.com/PuliPHP/status/685132540588507137) with the latest json-schema


## 0.6.3 - 2016-01-04

### Changed

- Adjust Puli dependencies


## 0.6.2 - 2016-01-04

### Changed

- Make Puli CLI a requirement


## 0.6.1 - 2016-01-03

### Changed

- More flexible Puli requirement


## 0.6.0 - 2015-12-30

### Changed

- Use [Puli](http://puli.io) for discovery
- Improved exception messages


## 0.5.0 - 2015-12-25

### Changed

- Updated message factory dependency (php-http/message)


## 0.4.0 - 2015-12-17

### Added

- Array condition evaluation in the Class Discovery

### Removed

- Message factories (moved to php-http/utils)


## 0.3.0 - 2015-11-18

### Added

- HTTP Async Client Discovery
- Stream factories

### Changed

- Discoveries and Factories are final
- Message and Uri factories have the type in their names
- Diactoros Message factory uses Stream factory internally

### Fixed

- Improved docblocks for API documentation generation


## 0.2.0 - 2015-10-31

### Changed

- Renamed AdapterDiscovery to ClientDiscovery


## 0.1.1 - 2015-06-13

### Fixed

- Bad HTTP Adapter class name for Guzzle 5


## 0.1.0 - 2015-06-12

### Added

- Initial release
