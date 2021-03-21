# Release Notes

## [Unreleased](https://github.com/enlightn/enlightn/compare/v1.19.0...master)

## [v1.19.0 (2021-03-16)](https://github.com/enlightn/enlightn/compare/v1.18.0...v1.19.0)

### Added
- Add ability to display exception stack trace of analyzers ([#57](https://github.com/enlightn/enlightn/pull/57))

## [v1.18.0 (2021-03-11)](https://github.com/enlightn/enlightn/compare/v1.17.0...v1.18.0)

### Changed
- Ignore HTTP errors for checking headers ([#56](https://github.com/enlightn/enlightn/pull/56))

## [v1.17.0 (2021-03-09)](https://github.com/enlightn/enlightn/compare/v1.16.0...v1.17.0)

### Added
- Add analyzer to detect publicly accessible env file ([#55](https://github.com/enlightn/enlightn/pull/55))

## [v1.16.0 (2021-02-28)](https://github.com/enlightn/enlightn/compare/v1.15.1...v1.16.0)

### Added
- Say hello to the Enlightn Github bot ([#53](https://github.com/enlightn/enlightn/pull/53))

## [v1.15.1 (2021-02-21)](https://github.com/enlightn/enlightn/compare/v1.15.0...v1.15.1)

### Fixed
- Fix trusted proxies bug ([#49](https://github.com/enlightn/enlightn/pull/49))

## [v1.15.0 (2021-02-16)](https://github.com/enlightn/enlightn/compare/v1.14.0...v1.15.0)

### Added
- Add WTFPL to license whitelist ([#46](https://github.com/enlightn/enlightn/pull/46))

## [v1.14.0 (2021-02-14)](https://github.com/enlightn/enlightn/compare/v1.13.0...v1.14.0)

### Added
- Add ability to define preload code ([#43](https://github.com/enlightn/enlightn/pull/43))

### Fixed
- Fix view caching analyzer condition ([#42](https://github.com/enlightn/enlightn/pull/42))

## [v1.13.0 (2021-02-10)](https://github.com/enlightn/enlightn/compare/v1.12.0...v1.13.0)

### Changed
- Relax class property check for mixed objects ([#39](https://github.com/enlightn/enlightn/pull/39))

## [v1.12.0 (2021-02-10)](https://github.com/enlightn/enlightn/compare/v1.11.0...v1.12.0)

### Added
- Allow Larastan version 0.7 ([#38](https://github.com/enlightn/enlightn/pull/38))

## [v1.11.0 (2021-02-07)](https://github.com/enlightn/enlightn/compare/v1.10.0...v1.11.0)

### Added
- Add ability to ignore errors and establish a baseline ([#36](https://github.com/enlightn/enlightn/pull/36))

## [v1.10.0 (2021-02-04)](https://github.com/enlightn/enlightn/compare/v1.9.0...v1.10.0)

### Added
- Add details to analyzer fail messages ([#32](https://github.com/enlightn/enlightn/pull/32))

## [v1.9.0 (2021-02-03)](https://github.com/enlightn/enlightn/compare/v1.8.0...v1.9.0)

### Added
- Add support for CI mode ([#29](https://github.com/enlightn/enlightn/pull/29))

## [v1.8.0 (2021-02-01)](https://github.com/enlightn/enlightn/compare/v1.7.1...v1.8.0)

### Added
- Make improvements to static analysis ([#26](https://github.com/enlightn/enlightn/pull/26))

## [v1.7.1 (2021-01-29)](https://github.com/enlightn/enlightn/compare/v1.7...v1.7.1)

### Fixed
- Fix percentage calculations ([#22](https://github.com/enlightn/enlightn/pull/22))

### Added
- Faster tests by adding paratest and remove un-needed services ([#20](https://github.com/enlightn/enlightn/pull/20))

## [v1.7 (2021-01-27)](https://github.com/enlightn/enlightn/compare/v1.6...v1.7)

### Added
- Add analyzer to detect syntax errors ([#19](https://github.com/enlightn/enlightn/pull/19))
- Support custom categories ([#18](https://github.com/enlightn/enlightn/pull/18))

## [v1.6 (2021-01-26)](https://github.com/enlightn/enlightn/compare/v1.5...v1.6)

### Fixed
- Fix crash when there is a syntax error in one of the app files ([#17](https://github.com/enlightn/enlightn/pull/17))

## [v1.5 (2021-01-26)](https://github.com/enlightn/enlightn/compare/v1.4...v1.5)

### Added
- Add CC0 and Unlicense to list of whitelisted licenses ([#15](https://github.com/enlightn/enlightn/pull/15))
- Add option to show all files in the Enlightn command ([#16](https://github.com/enlightn/enlightn/pull/16))

## [v1.4 (2021-01-22)](https://github.com/enlightn/enlightn/compare/v1.3...v1.4)

### Added
- Add ability to exclude analyzers from reporting for CI/CD ([#12](https://github.com/enlightn/enlightn/pull/12))

### Fixed
- Add function check for opcache_get_configuration so it gracefully fails ([#10](https://github.com/enlightn/enlightn/pull/10))
- Fix logo for white terminals ([#11](https://github.com/enlightn/enlightn/pull/11))

## [v1.3 (2021-01-22)](https://github.com/enlightn/enlightn/compare/v1.2...v1.3)

### Added
- Add trinary maybe logic for PHPStan ([#9](https://github.com/enlightn/enlightn/pull/9))

## [v1.2 (2021-01-21)](https://github.com/enlightn/enlightn/compare/v1.1...v1.2)

### Changed
- Improved detection of HTTPS only apps ([#8](https://github.com/enlightn/enlightn/pull/8))

## [v1.1 (2021-01-20)](https://github.com/enlightn/enlightn/compare/v1.0...v1.1)

### Added
- Failing mode for CI ([#3](https://github.com/enlightn/enlightn/pull/3))

### Changed
- Skip XSS analyzer in local ([#6](https://github.com/enlightn/enlightn/pull/6))
- Replace SensioLabs security checker with Enlightn's own security checker ([#5](https://github.com/enlightn/enlightn/pull/5))

### Fixed
- Fix analyzer percentage computation ([#7](https://github.com/enlightn/enlightn/pull/7))
