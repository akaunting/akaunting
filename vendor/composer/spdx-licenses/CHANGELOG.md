# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [main]

  ...

## [1.5.5] 2020-12-03

  * Changed: updated licenses list to SPDX 3.11

## [1.5.4] 2020-07-15

  * Changed: updated licenses list to SPDX 3.9

## [1.5.3] 2020-02-14

  * Changed: updated licenses list to SPDX 3.8

## [1.5.2] 2019-07-29

  * Changed: updated licenses list to SPDX 3.6

## [1.5.1] 2019-03-26

  * Changed: updated licenses list to SPDX 3.4

## [1.5.0] 2018-11-01

  * Changed: updated licenses list to SPDX 3.3

## [1.4.0] 2018-05-04

  * Changed: updated licenses list to SPDX 3.1

## [1.3.0] 2018-01-31

  * Added: `SpdxLicenses::getLicenses` to get the whole list of methods.
  * Changed: license identifiers are now case insensitive.

## [1.2.0] 2018-01-03

  * Added: deprecation status for all licenses and a `SpdxLicenses::isDeprecatedByIdentifier` method.
  * Changed: updated licenses list to SPDX 3.0.

## [1.1.6] 2017-04-03

  * Changed: updated licenses list.

## [1.1.5] 2016-09-28

  * Changed: updated licenses list.

## [1.1.4] 2016-05-04

  * Changed: updated licenses list.

## [1.1.3] 2016-03-25

  * Changed: updated licenses list.
  * Changed: dropped `test` namespace.
  * Changed: tedious small things.

## [1.1.2] 2015-10-05

  * Changed: updated licenses list.

## [1.1.1] 2015-09-07

  * Changed: improved performance when looking up just one license.
  * Changed: updated licenses list.

## [1.1.0] 2015-07-17

  * Changed: updater now sorts licenses and exceptions by key.
  * Changed: filenames now class constants of SpdxLicenses (`LICENSES_FILE` and `EXCEPTIONS_FILE`).
  * Changed: resources directory now available via static method `SpdxLicenses::getResourcesDir()`.
  * Changed: updated licenses list.
  * Changed: removed json-schema requirement.

## [1.0.0] 2015-07-15

  * Break: the following classes and namespaces were renamed:
    - Namespace: `Composer\Util` -> `Composer\Spdx`
    - Classname: `SpdxLicense` -> `SpdxLicenses`
    - Classname: `SpdxLicenseTest` -> `SpdxLicensesTest`
    - Classname: `Updater` -> `SpdxLicensesUpdater`
  * Changed: validation via regex implementation instead of lexer.

[main]: https://github.com/composer/spdx-licenses/compare/1.5.5...main
[1.5.5]: https://github.com/composer/spdx-licenses/compare/1.5.4...1.5.5
[1.5.4]: https://github.com/composer/spdx-licenses/compare/1.5.3...1.5.4
[1.5.3]: https://github.com/composer/spdx-licenses/compare/1.5.2...1.5.3
[1.5.2]: https://github.com/composer/spdx-licenses/compare/1.5.1...1.5.2
[1.5.1]: https://github.com/composer/spdx-licenses/compare/1.5.0...1.5.1
[1.5.0]: https://github.com/composer/spdx-licenses/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/composer/spdx-licenses/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/composer/spdx-licenses/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/composer/spdx-licenses/compare/1.1.6...1.2.0
[1.1.6]: https://github.com/composer/spdx-licenses/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/composer/spdx-licenses/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/composer/spdx-licenses/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/composer/spdx-licenses/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/composer/spdx-licenses/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/composer/spdx-licenses/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/composer/spdx-licenses/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/composer/spdx-licenses/compare/0281a7fe7820c990db3058844e7d448d7b70e3ac...1.0.0
