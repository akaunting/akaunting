<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/math"><img src="https://img.shields.io/travis/hoaproject/math/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/math?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/math/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/math"><img src="https://img.shields.io/packagist/dt/hoa/math.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/math.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Math

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Math)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/math)

This library provides tools around mathematical operations.

[Learn more](https://central.hoa-project.net/Documentation/Library/Math).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/math`](https://packagist.org/packages/hoa/math):

```sh
$ composer require hoa/math '~1.0'
```

For more installation procedures, please read [the Source
page](https://hoa-project.net/Source.html).

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/hoa test:run
```

For more information, please read the [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html).

## Quick usage

We propose a quick overview of one feature: evaluation of arithmetical
expressions.

### Evaluation of arithmetical expressions

The `hoa://Library/Math/Arithmetic.pp` describes the form of an arithmetical
expression. Therefore, we will use the classical workflow when manipulating a
grammar, that involves the [`Hoa\Compiler`
library](https://central.hoa-project.net/Resource/Library/Compiler) and the
`Hoa\Math\Visitor\Arithmetic` class.

```php
// 1. Load the compiler.
$compiler = Hoa\Compiler\Llk::load(
    new Hoa\File\Read('hoa://Library/Math/Arithmetic.pp')
);

// 2. Load the visitor, aka the “evaluator”.
$visitor    = new Hoa\Math\Visitor\Arithmetic();

// 3. Declare the expression.
$expression = '1 / 2 / 3 + 4 * (5 * 2 - 6) * PI / avg(7, 8, 9)';

// 4. Parse the expression.
$ast        = $compiler->parse($expression);

// 5. Evaluate.
var_dump(
    $visitor->visit($ast)
);

/**
 * Will output:
 *     float(6.4498519738463)
 */

// Bonus. Print the AST of the expression.
$dump = new Hoa\Compiler\Visitor\Dump();
echo $dump->visit($ast);

/**
 * Will output:
 *     >  #addition
 *     >  >  #division
 *     >  >  >  token(number, 1)
 *     >  >  >  #division
 *     >  >  >  >  token(number, 2)
 *     >  >  >  >  token(number, 3)
 *     >  >  #multiplication
 *     >  >  >  token(number, 4)
 *     >  >  >  #multiplication
 *     >  >  >  >  #group
 *     >  >  >  >  >  #substraction
 *     >  >  >  >  >  >  #multiplication
 *     >  >  >  >  >  >  >  token(number, 5)
 *     >  >  >  >  >  >  >  token(number, 2)
 *     >  >  >  >  >  >  token(number, 6)
 *     >  >  >  >  #division
 *     >  >  >  >  >  token(constant, PI)
 *     >  >  >  >  >  #function
 *     >  >  >  >  >  >  token(id, avg)
 *     >  >  >  >  >  >  token(number, 7)
 *     >  >  >  >  >  >  token(number, 8)
 *     >  >  >  >  >  >  token(number, 9)
 */
```

We can add functions and constants on the visitor, thanks to the `addFunction`
and `addConstant` methods. Thus, we will add the `rand` function (with 2
parameters) and the `ANSWER` constant, set to 42:

```php
$visitor->addFunction('rand', function ($min, $max) {
    return mt_rand($min, $max);
});
$visitor->addConstant('ANSWER', 42);

$expression = 'rand(ANSWER / 2, ANSWER * 2)'
var_dump(
    $visitor->visit($compiler->parse($expression))
);

/**
 * Could output:
 *     int(53)
 */
```

## Documentation

The
[hack book of `Hoa\Math`](https://central.hoa-project.net/Documentation/Library/Math) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev hoa/devtools
$ vendor/bin/hoa devtools:documentation --open
```

More documentation can be found on the project's website:
[hoa-project.net](https://hoa-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#hoaproject`](https://webchat.freenode.net/?channels=#hoaproject)
    IRC channel,
  * On the forum at [users.hoa-project.net](https://users.hoa-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.

## Related projects

The following projects are using this library:

  * [PSIH & PMSIpilot](http://www.psih.fr/en/),
    PSIH is the leading French integrator of business intelligence solutions for
    the healthcare sector,
  * [PHP Telegram Bot](https://github.com/akalongman/php-telegram-bot/),
    Telegram bot based on the official Telegram Bot API.
