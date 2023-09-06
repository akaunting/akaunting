# ZipStream-PHP

[![Main Branch](https://github.com/maennchen/ZipStream-PHP/actions/workflows/branch_main.yml/badge.svg)](https://github.com/maennchen/ZipStream-PHP/actions/workflows/branch_main.yml)
[![Coverage Status](https://coveralls.io/repos/github/maennchen/ZipStream-PHP/badge.svg?branch=main)](https://coveralls.io/github/maennchen/ZipStream-PHP?branch=main)
[![Latest Stable Version](https://poser.pugx.org/maennchen/zipstream-php/v/stable)](https://packagist.org/packages/maennchen/zipstream-php)
[![Total Downloads](https://poser.pugx.org/maennchen/zipstream-php/downloads)](https://packagist.org/packages/maennchen/zipstream-php)
[![Financial Contributors on Open Collective](https://opencollective.com/zipstream/all/badge.svg?label=financial+contributors)](https://opencollective.com/zipstream) [![License](https://img.shields.io/github/license/maennchen/zipstream-php.svg)](LICENSE)

## Unstable Branch

The `main` branch is not stable. Please see the
[releases](https://github.com/maennchen/ZipStream-PHP/releases) for a stable
version.

## Overview

A fast and simple streaming zip file downloader for PHP. Using this library will
save you from having to write the Zip to disk. You can directly send it to the
user, which is much faster. It can work with S3 buckets or any PSR7 Stream.

Please see the [LICENSE](LICENSE) file for licensing and warranty information.

## Installation

Simply add a dependency on maennchen/zipstream-php to your project's
`composer.json` file if you use Composer to manage the dependencies of your
project. Use following command to add the package to your project's dependencies:

```bash
composer require maennchen/zipstream-php
```

## Usage

For detailed instructions, please check the
[Documentation](https://maennchen.github.io/ZipStream-PHP/).

```php
// Autoload the dependencies
require 'vendor/autoload.php';

// create a new zipstream object
$zip = new ZipStream\ZipStream(
    outputName: 'example.zip',

    // enable output of HTTP headers
    sendHttpHeaders: true,
);

// create a file named 'hello.txt'
$zip->addFile(
    fileName: 'hello.txt',
    data: 'This is the contents of hello.txt',
);

// add a file named 'some_image.jpg' from a local file 'path/to/image.jpg'
$zip->addFileFromPath(
    fileName: 'some_image.jpg',
    path: 'path/to/image.jpg',
);

// finish the zip stream
$zip->finish();
```

## Upgrade to version 3.0.0

### General

- Minimum PHP Version: `8.1`
- Only 64bit Architecture is supported.
- The class `ZipStream\Option\Method` has been replaced with the enum
  `ZipStream\CompressionMethod`.
- Most clases have been flagged as `@internal` and should not be used from the
  outside.
  If you're using internal resources to extend this library, please open an
  issue so that a clean interface can be added & published.
  The externally available classes & enums are:
  - `ZipStream\CompressionMethod`
  - `ZipStream\Exception*`
  - `ZipStream\ZipStream`

### Archive Options

- The class `ZipStream\Option\Archive` has been replaced in favor of named
  arguments in the `ZipStream\ZipStream` constuctor.
- The archive options `largeFileSize` & `largeFileMethod` has been removed. If
  you want different `compressionMethods` based on the file size, you'll have to
  implement this yourself.
- The archive option `httpHeaderCallback` changed the type from `callable` to
  `Closure`.
- The archive option `zeroHeader` has been replaced with the option
  `defaultEnableZeroHeader` and can be overridden for every file. Its default
  value changed from `false` to `true`.
- The archive option `statFiles` was removed since the library no longer checks
  filesizes this way.
- The archive option `deflateLevel` has been replaced with the option
  `defaultDeflateLevel` and can be overridden for every file.
- The first argument (`name`) of the `ZipStream\ZipStream` constuctor has been
  replaced with the named argument `outputName`.
- Headers are now also sent if the `outputName` is empty. If you do not want to
  automatically send http headers, set `sendHttpHeaders` to `false`.

### File Options

- The class `ZipStream\Option\File` has been replaced in favor of named
  arguments in the `ZipStream\ZipStream->addFile*` functions.
- The file option `method` has been renamed to `compressionMethod`.
- The file option `time` has been renamed to `lastModificationDateTime`.
- The file option `size` has been renamed to `maxSize`.

## Upgrade to version 2.0.0

https://github.com/maennchen/ZipStream-PHP/tree/2.0.0#upgrade-to-version-200

## Upgrade to version 1.0.0

https://github.com/maennchen/ZipStream-PHP/tree/2.0.0#upgrade-to-version-100

## Contributing

ZipStream-PHP is a collaborative project. Please take a look at the
[.github/CONTRIBUTING.md](.github/CONTRIBUTING.md) file.

## Version Support

Versions are supported according to the table below.

Please do not open any pull requests contradicting the current version support
status.

Careful: Always check the `README` on `main` for up-to-date information.

| Version | New Features | Bugfixes | Security |
|---------|--------------|----------|----------|
| *3*     | ✓            | ✓        | ✓        |
| *2*     | ✗            | ✓        | ✓        |
| *1*     | ✗            | ✗        | ✓        |
| *0*     | ✗            | ✗        | ✗        |

This library aligns itself with the PHP core support. New features and bugfixes
will only target PHP versions according to their current status.

See: https://www.php.net/supported-versions.php

## About the Authors

- Paul Duncan <pabs@pablotron.org> - https://pablotron.org/
- Jonatan Männchen <jonatan@maennchen.ch> - https://maennchen.dev
- Jesse G. Donat <donatj@gmail.com> - https://donatstudios.com
- Nicolas CARPi <nico-git@deltablot.email> - https://www.deltablot.com
- Nik Barham <nik@brokencube.co.uk> - https://www.brokencube.co.uk

## Contributors

### Code Contributors

This project exists thanks to all the people who contribute.
[[Contribute](.github/CONTRIBUTING.md)].
<a href="https://github.com/maennchen/ZipStream-PHP/graphs/contributors"><img src="https://opencollective.com/zipstream/contributors.svg?width=890&button=false" /></a>

### Financial Contributors

Become a financial contributor and help us sustain our community. [[Contribute](https://opencollective.com/zipstream/contribute)]

#### Individuals

<a href="https://opencollective.com/zipstream"><img src="https://opencollective.com/zipstream/individuals.svg?width=890"></a>

#### Organizations

Support this project with your organization. Your logo will show up here with a link to your website. [[Contribute](https://opencollective.com/zipstream/contribute)]

<a href="https://opencollective.com/zipstream/organization/0/website"><img src="https://opencollective.com/zipstream/organization/0/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/1/website"><img src="https://opencollective.com/zipstream/organization/1/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/2/website"><img src="https://opencollective.com/zipstream/organization/2/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/3/website"><img src="https://opencollective.com/zipstream/organization/3/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/4/website"><img src="https://opencollective.com/zipstream/organization/4/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/5/website"><img src="https://opencollective.com/zipstream/organization/5/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/6/website"><img src="https://opencollective.com/zipstream/organization/6/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/7/website"><img src="https://opencollective.com/zipstream/organization/7/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/8/website"><img src="https://opencollective.com/zipstream/organization/8/avatar.svg"></a>
<a href="https://opencollective.com/zipstream/organization/9/website"><img src="https://opencollective.com/zipstream/organization/9/avatar.svg"></a>
