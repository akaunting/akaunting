# Change Log

## [3.1.0] - 2020-11-24

### Added
- Added `CronExpression::getParts()` method to get parts of the expression as an array (#83)

### Changed
- Changed to Interfaces for some type hints (#97, #86)
- Dropped minimum PHP version to 7.2
- Few syntax changes for phpstan compatibility (#93)

### Fixed
- N/A

### Deprecated
- Deprecated `CronExpression::factory` in favor of the constructor (#56)
- Deprecated `CronExpression::YEAR` as a formality, the functionality is already removed (#87)

## [3.0.1] - 2020-10-12
### Added
- Added support for PHP 8 (#92)
### Changed
- N/A
### Fixed
- N/A

## [3.0.0] - 2020-03-25

**MAJOR CHANGE** - In previous versions of this library, setting both a "Day of Month" and a "Day of Week" would be interpreted as an `AND` statement, not an `OR` statement. For example:

`30 0 1 * 1`

would evaluate to "Run 30 minutes after the 0 hour when the Day Of Month is 1 AND a Monday" instead of "Run 30 minutes after the 0 hour on Day Of Month 1 OR a Monday", where the latter is more inline with most cron systems. This means that if your cron expression has both of these fields set, you may see your expression fire more often starting with v3.0.0. 

### Added
- Additional docblocks for IDE and documentation
- Added phpstan as a development dependency
- Added a `Cron\FieldFactoryInterface` to make migrations easier (#38)
### Changed
- Changed some DI testing during TravisCI runs
- `\Cron\CronExpression::determineTimezone()` now checks for `\DateTimeInterface` instead of just `\DateTime`
- Errors with fields now report a more human-understandable error and are 1-based instead of 0-based
- Better support for `\DateTimeImmutable` across the library by typehinting for `\DateTimeInterface` now
- Literals should now be less case-sensative across the board
- Changed logic for when both a Day of Week and a Day of Month are supplied to now be an OR statement, not an AND
### Fixed
- Fixed infinite loop when determining last day of week from literals
- Fixed bug where single number ranges were allowed (ex: `1/10`)
- Fixed nullable FieldFactory in CronExpression where no factory could be supplied
- Fixed issue where logic for dropping seconds to 0 could lead to a timezone change

## [2.3.1] - 2020-10-12
### Added
- Added support for PHP 8 (#92)
### Changed
- N/A
### Fixed
- N/A

## [2.3.0] - 2019-03-30
### Added
- Added support for DateTimeImmutable via DateTimeInterface
- Added support for PHP 7.3
- Started listing projects that use the library
### Changed
- Errors should now report a human readable position in the cron expression, instead of starting at 0
### Fixed
- N/A

## [2.2.0] - 2018-06-05
### Added
- Added support for steps larger than field ranges (#6)
## Changed
- N/A
### Fixed
- Fixed validation for numbers with leading 0s (#12)

## [2.1.0] - 2018-04-06
### Added
- N/A
### Changed
- Upgraded to PHPUnit 6 (#2)
### Fixed
- Refactored timezones to deal with some inconsistent behavior (#3)
- Allow ranges and lists in same expression (#5)
- Fixed regression where literals were not converted to their numerical counterpart (#)

## [2.0.0] - 2017-10-12
### Added
- N/A

### Changed
- Dropped support for PHP 5.x
- Dropped support for the YEAR field, as it was not part of the cron standard

### Fixed
- Reworked validation for all the field types
- Stepping should now work for 1-indexed fields like Month (#153)

## [1.2.0] - 2017-01-22
### Added
- Added IDE, CodeSniffer, and StyleCI.IO support

### Changed
- Switched to PSR-4 Autoloading

### Fixed
- 0 step expressions are handled better
- Fixed `DayOfMonth` validation to be more strict
- Typos

## [1.1.0] - 2016-01-26
### Added
- Support for non-hourly offset timezones 
- Checks for valid expressions

### Changed
- Max Iterations no longer hardcoded for `getRunDate()`
- Supports DateTimeImmutable for newer PHP verions

### Fixed
- Fixed looping bug for PHP 7 when determining the last specified weekday of a month

## [1.0.3] - 2013-11-23
### Added
- Now supports expressions with any number of extra spaces, tabs, or newlines

### Changed
- Using static instead of self in `CronExpression::factory`

### Fixed
- Fixes issue [#28](https://github.com/mtdowling/cron-expression/issues/28) where PHP increments of ranges were failing due to PHP casting hyphens to 0
- Only set default timezone if the given $currentTime is not a DateTime instance ([#34](https://github.com/mtdowling/cron-expression/issues/34))
