[![Build Status](https://travis-ci.com/sanmai/Protocol.svg?branch=master)](https://travis-ci.com/sanmai/Protocol)
[![Latest Stable Version](https://poser.pugx.org/sanmai/hoa-protocol/v/stable)](https://packagist.org/packages/sanmai/hoa-protocol)

# Hoa\Protocol

This library provides the `hoa://` protocol, which is a way to abstract resource accesses. [Learn more](https://central.hoa-project.net/Documentation/Library/Protocol).

This particular fork aims to solve some deficiencies of the original library, while otherwise being a feature match to the original package. Specifically, this fork comes without the global `resolve()` function, which is known to cause a conflict with some versions of Laravel.

This library is routinely tested to work with PHP 7.0-7.4. Please report any issues.

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to...

```sh
composer require sanmai/hoa-protocol
```

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
composer install
```

Then, to run all the test suites:

```sh
vendor/bin/atoum -d Test
```

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.
