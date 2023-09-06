# Change Log


All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]


## [4.2.0] - 2023-08-16

### Added

- Teller object to help legacy applications


## [4.1.1] - 2023-04-11

### Fixed

- Optimized result representation for BcMathCalculator
- Use sprintf for float to string conversion in swap exchange
- Fix rounding to unit with value between 10 and 49
- Return type for DecimalMoneyFormatter::format is numeric-string
- Money::ratioOf throws exception if currencies don't match.
- Updated currencies: added SLE and VED, removed HRK.
- Do not let php-http/discovery change composer.json.


## [4.1.0] - 2022-12-19

### Added

- Full PHP8.2 compatibility

### Fixed

- Fix parsing amounts with leading zero's
- Fix invalid exception message in DecimalMoneyParser
- Upgrade enchant to newer version
- Support Psalm 5.3
- Fix ROUND_UP and ROUND_DOWN in roundToUnit

### Changes

- `composer.lock` is not committed anymore
- MoneyFactory now also contains crypto-currencies

### Added

- `CryptoCurrencies` class that serves crypto-currencies from [moneyphp/crypto-currencies](https://github.com/moneyphp/crypto-currencies)


## [4.0.5] - 2022-08-11

### Fixed

- Enabled zend.assertions during CI
- Fixed invalid assertions


## [4.0.4] - 2022-05-18

### Added

- Full PHP8.1 compatibility (#670)

### Changes

- Dropped mutation testing as the library owners are unfamiliar with it
- CHANGELOG BC change improvement

## [4.0.3] - 2021-12-01

### Fixed

- `jsonSerialize` return types, resulting in no PHP 8.1 warnings.
- Documentation CI pipeline failure.


## [4.0.2] - 2021-06-30

### Changes

- Added all extensions to `composer.json`, including those bundled with PHP.

### Fixed

- Remove all `empty` uses. Money values of '0' gave an assertion error in decimal formats (#655)


## [4.0.1] - 2021-05-25

### Changed

- Allow `int` as multiplier for `Money::multiply`
- Allow `int` as divisor for `Money::divide`


## [4.0.0] - 2021-05-17

### Added

- PHP8.0 compatibility (#633 and #619)
- Recommendation to use a type-checker like psalm or phpstan
- Rounding to units
- `Converter#convertAndReturnWithCurrencyPair` to get the combination of the converted money and currency pair
- `Converter#convertAgainstCurrencyPair` to convert against a currency pair

### Changed

- **[BC break]** BC Math required as it is the default calculator
- **[BC break]** The methods `multiply` and `divide` do not accept floating points any more. Callers are required to
  convert a float to string (e.g. `sprintf('%.14F', $float)`) before calling these methods.
- **[BC break]** The constructor of the `FixedExchange` does not accept floating points any more. Callers are required to
convert a float to string (e.g. `sprintf('%.14F', $float)`) before calling the constructor.
- Allow multiple arguments to `Money#isSameCurrency`
- Renamed second parameter of `Parser#parse` to `$fallbackCurrency`

### Fixed

- Fix GMP multiply and divide by negative (#640 #626)
- Currency code must be uppercase (#639 #638 #637)
- The CPU no longer overheats when using this library (#634)
- No longer allowing null amount (#615)
- Update cached currencies (#583)
- Only numeric strings allowed (#575)
- Calculator and Number are internal (#465)
- Negative ratio in `Money#allocate()` is now allowed (#258)

### Removed

- **[BC break]** Removed `PhpCalculator`
- **[BC break]** Removed `Currency#isAvailableWithin()`
- **[BC break]** Removed string as allowed type for second parameter to `MoneyParser#parse`, only Currency objects are accepted now
- **[BC break]** Completely remove float usage, methods now return numeric-strings


## [3.3.1] - 2019-03-13

### Fixed

- GMP: division of negative values
- GMP: compare 0.x value


## [3.3.0] - 2019-12-27

### Changed

- Added types for `Money` to be understood as pure/immutable downstream (#576)

### Fixed

- JSON serialization (#551)
- Several documentation fixes
- Minor fixes


## [3.2.1] - 2019-02-07

### Changed

- `Money::allocate` now maintains keys of ratios array
- All parsers now emit a deprecation warning when passing currency as string

### Fixed

- Docs fix : plus sign in numeric strings is allowed
- Added ext-json as required extension
- Throw exception in case of empty currency
- BCMath calculator now uses scale parameters for addition and subtracting
- Fixed allocation remainder bug
- Added PHP 7.3 in test suite
- Fixed dockerignore to ignore Dockerfile
- Fixed Bitcoin parsing bug when using trailing zeros


## [3.2.0] - 2018-12-05

### Added

- [Exchanger](https://github.com/florianv/exchanger) exchange
- Generated static factory to help IDEs understand code like `Money::EUR(500)`
- Aggregation functions (min, max, avg, sum)

### Changed

- `Money::add` and `Money::subtract` now accept variadic arguments

### Fixed

- Division causing unnecessary fractional parts
- Numeric comparison for negative numbers


## [3.1.3] - 2018-02-16

### Fixed

- Allocation when the amount is smaller than the number of ratios


## [3.1.2] - 2018-02-16

### Added

- `Number::fromNumber` to be used when the actual type is not known

### Changed

- Refactored `Number` usage to make the code cleaner and use less casting

### Fixed

- Float cast to string issue on certain locales


## [3.1.1] - 2018-01-19

### Fixed

- Float cast to string issue on certain locales
- Deal with numbers represented with E-XX


## [3.1.0] - 2018-01-10

### Added

- CurrencyList to instantiate in-memory currencies
- modulus method to Money
- ratioOf method to Money
- Comparator for easier testing Money object with PHPUnit
- IntlLocalizedDecimalParser and IntlLocalizedDecimalFormatter

### Changed

- `MoneyParser::parse` method now expects a Currency object
- Dropped PHP 5.5

### Deprecated

- Passing currency code as string to `MoneyParser::parse`

### Fixed

- Do not allocate remainder to a ratio of zero
- Conversion result is always 0 when subunit difference is large enough
- Unexpected result when converting small Bitcoin amounts
- Fixed StyleCI being too aggressive


## [3.0.9] - 2017-11-05

### Fixed

- Bitcoin currency symbol


## [3.0.8] - 2017-10-03

### Fixed

- Rounding issue in Number class.
- Reduce composer package file size by leaving out docs and logo.
- Missing Travis tests for PHP 7.2.


## [3.0.7] - 2017-08-07

### Changed

- Currencies


## [3.0.6] - 2017-07-25

### Added

- IndirectExchange: a way to get an exchange rate through a minimal set of intermediate conversions.

### Fixed

- Tests for HHVM
- Incorrect documentation on Bitcoin parser


## [3.0.5] - 2017-04-26

### Added

- numericCodeFor method to ISOCurrencies


## [3.0.4] - 2017-04-21

### Added

- Negative method

### Changed

- Updated ISO Currencies
- Removed old Belarusian ruble from ISOCurrencies (BYR)

### Fixed

- ISOCurrencies will no longer have a blank currency
- Double symbol when formatting negative Bitcoin amounts


## [3.0.3] - 2017-03-22

### Fixed

- Parsing empty strings and number starting or ending with a decimal point for DecimalMoneyParser
- Parsing zero for DecimalMoneyParser
- Multiplying and dividing with a locale that use commas as separator

## [3.0.2] - 2017-03-11

### Fixed

- BCMath / GMP: comparing values smaller than one
- GMP: multiplying with zero
- ISOCurrencies: minor refactoring, remove duplication of code


## [3.0.1] - 2017-02-14

### Added

- Reversed Currencies Exchange to try resolving reverse of a currency pair
- Documentation on allowed integer(ish) values when constructing Money

### Fixed

- Passing integer validation when chunk started with a dash
- Passing integer validation when the fractional part started with a dash
- Formatting problem for Bitcoin currency with small amounts in PHP < 7.0
- Money constructed from a string with fractional zeroes equals to a Money constructed without the fractional part (eg. `'5.00'` and `'5'`)


## [3.0.0] - 2016-10-26

### Added

- DecimalMoneyFormatter: returns locale-independent raw decimal string

### Changed

- **[BC break]** Replaced StringToUnitsParser with DecimalMoneyParser
- **[BC break]** Moved `Money\Exception\Exception` to `Money\Exception`
- **[BC break]** UnkownCurrencyException is now DomainException instead of RuntimeException
- **[Doctrine break]** In `Currency` the private variable `name` was renamed to `code`, which could break your Doctrine mapping if you are using embeddables or any other Reflection related implementation.


## [3.0.0-beta.3] - 2016-10-04

### Added

- FixedExchange: returns fixed exchange rates based on a list (array)

### Changed

- **[BC break]** Convert method now moved to its own class: Converter
- **[BC break]** Exchange had one method getCurrencyPair which is now renamed to quote
- Minor documentation issues

### Fixed

- Integer detection when the number overflows the integer type and contains zeros
- Rounding numbers containg trailing zeros
- Converting Money to currency with different number of subunits


## [3.0.0-beta.2] - 2016-08-03

### Added

- PHP Spec tests
- absolute method to Money and Calculator
- subunitFor method to Currencies
- Currencies now extends IteratorAggregate
- Library exceptions now implement a common interface
- Formatter and Parser implementation are now rounding half up

### Changed

- **[BC break]** Dropped PHP 5.4 support
- **[BC break]** Intl and Bitcoin formatters and parsers now require Currencies
- ISOCurrencies now uses moneyphp/iso-currencies as currency data source

### Fixed

- Documentation to be inline with upcoming version 3
- Rounding issues in calculators with negative numbers
- Formatting and parser issues for amounts and numbers with a trailing zero
- Improved many exception messages
- Registration of own Calculator implementations


## [3.0.0-beta] - 2016-03-01

### Added

- Bitcoin parser and formatter
- Also checking tests folder for StyleCI

### Fixed

- Currencies are now included in the repo
- Currency list generation moved to dev dependency: reduces repo size
- BC Math calculator adding and subtracting failed when bcscale was set
- Parsing zero for StringToUnitsParser


## 3.0.0-alpha - 2016-02-04

### Added

- Currency repositories (ISO currencies included)
- Money exchange (including [Swap](https://github.com/florianv/swap) implementation)
- Money formatting (including intl formatter)
- Money parsing (including intl parser)
- Big integer support utilizing different, transparent calculation logic upon availability (bcmath, gmp, plain php)
- Money and Currency implements JsonSerializable
- Rounding up and down
- Allocation to N targets

### Changed

- **[BC break]** Money::getAmount() returns a string instead of an int value
- **[BC break]** Moved stringToUnits to StringToUnitsParser parser
- Library requires at least PHP 5.4
- Library uses PSR-4

### Fixed

- Integer overflow

### Removed

- **[BC break]** UnkownCurrency exception
- **[BC break]** Currency list is now provided by [umpirsky/currency-list](https://github.com/umpirsky/currency-list/)
- **[BC break]** RoundingMode class
- **[BC break]** Announced deprecations are removed (Currency::getName, CurrencyPair::getRatio, Money::getUnits)


## Pre 3.0

- 2015-03-23 Minimum php version is now 5.4
- 2015-03-23 JsonSerializable
- (... missing changelog because who remembers to document stuff anyway?)
- 2014-03-22 Removed \Money\InvalidArgumentException in favour of plain old InvalidArgumentException
- 2014-03-22 Introduce RoundingMode object, used to specify desired rounding
- 2014-03-22 Introduced RoundingMode backwards compatible API changes to Money::multiply and Money::divide
- 2014-03-22 Allow RoundingMode to be specified when converting currencies
- 2014-03-22 CurrencyPair has an equals() method
- 2013-10-13 Base currency and counter currency in CurrencyPair named correctly.
- 2013-01-08 Removed the Doctrine2\MoneyType helper, to be replaced by something better in the future. It's available
             at https://gist.github.com/4485025 in case you need it.
- 2013-01-08 Use vendor/autoload.php instead of lib/bootstrap.php (or use PSR-0 autolaoding)
- 2012-12-10 Renamed Money::getUnits() to Money::getAmount()

[Unreleased]: https://github.com/moneyphp/money/compare/v4.1.1...HEAD
[4.2.0]: https://github.com/moneyphp/money/compare/v4.1.1...v4.2.0
[4.1.1]: https://github.com/moneyphp/money/compare/v4.1.0...v4.1.1
[4.1.0]: https://github.com/moneyphp/money/compare/v4.0.5...v4.1.0
[4.0.5]: https://github.com/moneyphp/money/compare/v4.0.4...v4.0.5
[4.0.4]: https://github.com/moneyphp/money/compare/v4.0.3...v4.0.4
[4.0.3]: https://github.com/moneyphp/money/compare/v4.0.2...v4.0.3
[4.0.2]: https://github.com/moneyphp/money/compare/v4.0.1...v4.0.2
[4.0.1]: https://github.com/moneyphp/money/compare/v4.0.0...v4.0.1
[4.0.0]: https://github.com/moneyphp/money/compare/v3.3.1...v4.0.0
[3.3.1]: https://github.com/moneyphp/money/compare/v3.3.0...v3.3.1
[3.3.0]: https://github.com/moneyphp/money/compare/v3.2.1...v3.3.0
[3.2.1]: https://github.com/moneyphp/money/compare/v3.2.0...v3.2.1
[3.2.0]: https://github.com/moneyphp/money/compare/v3.1.3...v3.2.0
[3.1.3]: https://github.com/moneyphp/money/compare/v3.1.2...v3.1.3
[3.1.2]: https://github.com/moneyphp/money/compare/v3.1.1...v3.1.2
[3.1.1]: https://github.com/moneyphp/money/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/moneyphp/money/compare/v3.0.9...v3.1.0
[3.0.9]: https://github.com/moneyphp/money/compare/v3.0.8...v3.0.9
[3.0.8]: https://github.com/moneyphp/money/compare/v3.0.7...v3.0.8
[3.0.7]: https://github.com/moneyphp/money/compare/v3.0.6...v3.0.7
[3.0.6]: https://github.com/moneyphp/money/compare/v3.0.5...v3.0.6
[3.0.5]: https://github.com/moneyphp/money/compare/v3.0.4...v3.0.5
[3.0.4]: https://github.com/moneyphp/money/compare/v3.0.3...v3.0.4
[3.0.3]: https://github.com/moneyphp/money/compare/v3.0.2...v3.0.3
[3.0.2]: https://github.com/moneyphp/money/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/moneyphp/money/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/moneyphp/money/compare/v3.0.0-beta.4...v3.0.0
[3.0.0-beta4]: https://github.com/moneyphp/money/compare/v3.0.0-beta.3...v3.0.0-beta.4
[3.0.0-beta3]: https://github.com/moneyphp/money/compare/v3.0.0-beta.2...v3.0.0-beta.3
[3.0.0-beta2]: https://github.com/moneyphp/money/compare/v3.0.0-beta...v3.0.0-beta.2
[3.0.0-beta]: https://github.com/moneyphp/money/compare/v3.0.0-alpha...v3.0.0-beta
