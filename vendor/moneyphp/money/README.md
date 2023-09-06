# Money

[![Latest Version](https://img.shields.io/github/release/moneyphp/money.svg?style=flat-square)](https://github.com/moneyphp/money/releases)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/php-http/message/CI?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/moneyphp/money.svg?style=flat-square)](https://packagist.org/packages/moneyphp/money)

[![Email](https://img.shields.io/badge/email-team@moneyphp.org-blue.svg?style=flat-square)](mailto:team@moneyphp.org)

![Money PHP](/resources/logo.png?raw=true)

PHP library to make working with money safer, easier, and fun!

> "If I had a dime for every time I've seen someone use FLOAT to store currency, I'd have $999.997634" -- [Bill Karwin](https://twitter.com/billkarwin/status/347561901460447232)

In short: You shouldn't represent monetary values by a float. Wherever
you need to represent money, use this Money value object. Since version
3.0 this library uses [strings internally](https://github.com/moneyphp/money/pull/136)
in order to support unlimited integers.

```php
<?php

use Money\Money;

$fiveEur = Money::EUR(500);
$tenEur = $fiveEur->add($fiveEur);

list($part1, $part2, $part3) = $tenEur->allocate([1, 1, 1]);
assert($part1->equals(Money::EUR(334)));
assert($part2->equals(Money::EUR(333)));
assert($part3->equals(Money::EUR(333)));
```

The documentation is available at http://moneyphp.org


## Requirements

This library requires the [BCMath PHP extension](https://www.php.net/manual/en/book.bc.php). There might be additional dependencies for specific feature, e.g. the
Swap exchange implementation, check the documentation for more information.

Version 4 requires PHP 8.0. For older version of PHP, use version 3 of this library.


## Install

Via Composer

```bash
$ composer require moneyphp/money
```


## Features

- JSON Serialization
- Big integer support utilizing different, transparent calculation logic upon availability (bcmath, gmp, plain php)
- Money formatting (including intl formatter)
- Currency repositories (ISO currencies included)
- Money exchange (including [Swap](http://swap.voutzinos.org) implementation)


## Documentation

Please see the [official documentation](http://moneyphp.org).


## Testing

We try to follow BDD and TDD, as such we use both [phpspec](http://www.phpspec.net) and [phpunit](https://phpunit.de) to test this library.

```bash
$ composer test
```

### Running the tests in Docker

Money requires a set of dependencies, so you might want to run it in Docker.

First, build the image locally:

```bash
$ docker build -t moneyphp .
```

Then run the tests:

```bash
$ docker run --rm -it -v $PWD:/app -w /app moneyphp vendor/bin/phpunit --exclude-group segmentation
```


## Contributing

We would love to see you helping us to make this library better and better.
Please keep in mind we do not use suffixes and prefixes in class names,
so not `CurrenciesInterface`, but `Currencies`. Other than that, Style CI will help you
using the same code style as we are using. Please provide tests when creating a PR and clear descriptions of bugs when filing issues.


## Security

If you discover any security related issues, please contact us at [team@moneyphp.org](mailto:team@moneyphp.org).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


## Acknowledgements

This library is heavily inspired by [Martin Fowler's Money pattern](http://martinfowler.com/eaaCatalog/money.html).
A special remark goes to [Mathias Verraes](https://github.com/mathiasverraes), without his contributions,
in code and via his [blog](http://verraes.net/#blog), this library would not be where it stands now.
