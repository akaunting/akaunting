# ChangeLog

All notable changes are documented in this file using the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [10.1.4] - 2023-08-31

### Fixed

* Exceptions of type `SebastianBergmann\Template\Exception` are now properly handled

## [10.1.3] - 2023-07-26

### Changed

* The result of `CodeCoverage::getReport()` is now cached

### Fixed

* Static analysis cache keys do not include configuration settings that affect source code parsing
* The Clover, Cobertura, Crap4j, and PHP report writers no longer create a `php:` directory when they should write to `php://stdout`, for instance

## [10.1.2] - 2023-05-22

### Fixed

* [#998](https://github.com/sebastianbergmann/php-code-coverage/pull/998): Group Use Declarations are not handled properly

## [10.1.1] - 2023-04-17

### Fixed

* [#994](https://github.com/sebastianbergmann/php-code-coverage/issues/994): Argument `$linesToBeIgnored` of `CodeCoverage::stop()` has no effect for files that are not executed at all

## [10.1.0] - 2023-04-13

### Added

* [#982](https://github.com/sebastianbergmann/php-code-coverage/issues/982): Add option to ignore lines from code coverage

### Deprecated

* The `SebastianBergmann\CodeCoverage\Filter::includeDirectory()`, `SebastianBergmann\CodeCoverage\Filter::excludeDirectory()`, and `SebastianBergmann\CodeCoverage\Filter::excludeFile()` methods are now deprecated

[10.1.4]: https://github.com/sebastianbergmann/php-code-coverage/compare/10.1.3...10.1.4
[10.1.3]: https://github.com/sebastianbergmann/php-code-coverage/compare/10.1.2...10.1.3
[10.1.2]: https://github.com/sebastianbergmann/php-code-coverage/compare/10.1.1...10.1.2
[10.1.1]: https://github.com/sebastianbergmann/php-code-coverage/compare/10.1.0...10.1.1
[10.1.0]: https://github.com/sebastianbergmann/php-code-coverage/compare/10.0.2...10.1.0
