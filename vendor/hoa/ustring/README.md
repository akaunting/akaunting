<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/ustring"><img src="https://img.shields.io/travis/hoaproject/ustring/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/ustring?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/ustring/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/ustring"><img src="https://img.shields.io/packagist/dt/hoa/ustring.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/ustring.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Ustring

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Ustring)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/ustring)

This library allows to manipulate UTF-8 strings easily with some search
algorithms.

[Learn more](https://central.hoa-project.net/Documentation/Library/Ustring).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/ustring`](https://packagist.org/packages/hoa/ustring):

```sh
$ composer require hoa/ustring '~4.0'
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

We propose a quick overview of two usages: manipulate UTF-8 strings and one
search algorithm.

### Natural UTF-8 strings manipulation

The `Hoa\Ustring\Ustring` class allows to manipulate easily UTF-8 strings in a
very natural way. This class implements the `\ArrayAccess`, `\Countable` and
`\IteratorAggregate` interfaces. We will use the following examples:

```php
$french   = new Hoa\Ustring\Ustring('Je t\'aime');
$arabic   = new Hoa\Ustring\Ustring('أحبك');
$japanese = new Hoa\Ustring\Ustring('私はあなたを愛して');
```

To get the first character, we will do:

```php
var_dump(
    $french[0],  // string(1) "J"
    $arabic[0],  // string(2) "أ"
    $japanese[0] // string(3) "私"
);
```

And to get the last character, we will do `[-1]`. It supports unbounded (and
modulo) indexes.

We note that it cares about text **direction**. Look at `$arabic[0]`, it returns
`أ` and not `ك`. To get the direction, we can use the
`Hoa\Ustring\Ustring::getDirection` method (which call the
`Hoa\Ustring\Ustring::getCharDirection` static method), it returns either
`Hoa\Ustring\Ustring::LTR` (`0`) or `Hoa\Ustring\Ustring::RTL` (`1`):

```php
var_dump(
    $french->getDirection(),  // int(0)
    $arabic->getDirection(),  // int(1)
    $japanese->getDirection() // int(0)
);
```

Text direction is also important for the `append`, `prepend`, `pad`… methods on
`Hoa\Ustring\Ustring` for example. 

To get the length of a string, we can use the `count` function:

```php
var_dump(
    count($french),  // int(9)
    count($arabic),  // int(4)
    count($japanese) // int(9)
);
```

We are also able to iterate over the string:

```php
foreach ($arabic as $letter) {
    var_dump($letter);
}

/**
 * Will output:
 *     string(2) "أ"
 *     string(2) "ح"
 *     string(2) "ب"
 *     string(2) "ك"
 */
```

Again, text direction is useful here. For `$arabic`, the iteration is done from
right to left.

Some static methods are helpful, such as `fromCode`, `toCode` or `isUtf8` on
`Hoa\Ustring\Ustring`:

```php
var_dump(
    Hoa\Ustring\Ustring::fromCode(0x1a9), // string(2) "Ʃ"
    Hoa\Ustring\Ustring::toCode('Ʃ'),     // int(425) == 0x1a9
    Hoa\Ustring\Ustring::isUtf8('Ʃ')      // bool(true)
);
```

We can also transform any text into ASCII:

```php
$emoji = new Hoa\Ustring\Ustring('I ❤ Unicode');
$maths = new Hoa\Ustring\Ustring('∀ i ∈ ℕ');

echo
    $emoji->toAscii(), "\n",
    $maths->toAscii(), "\n";

/**
 * Will output:
 *     I (heavy black heart) Unicode
 *     (for all) i (element of) N
 */
```

### Search algorithm

The `Hoa\Ustring\Search` implements search algorithms on strings.

For example, the `Hoa\Ustring\Search::approximated` method make a search by
approximated patterns (with *k* differences based upon the principle diagonal
monotony). If we search the word `GATAA` in `CAGATAAGAGAA` with 1 difference, we
will do:

```php
$search = Hoa\Ustring\Search::approximated(
    $haystack = 'CAGATAAGAGAA',
    $needle   = 'GATAA',
    $k        = 1
);
$solutions = array();

foreach ($search as $pos) {
    $solutions[] = substr($haystack, $pos['i'], $pos['l']);
}
```

We will found `AGATA`, `GATAA`, `ATAAG` and `GAGAA`.

The result is not very handy but the algorithm is much optimized and found many
applications.

## Documentation

The
[hack book of `Hoa\Ustring`](https://central.hoa-project.net/Documentation/Library/Ustring) contains
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
