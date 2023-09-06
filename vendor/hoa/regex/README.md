<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/regex"><img src="https://img.shields.io/travis/hoaproject/regex/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/regex?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/regex/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/regex"><img src="https://img.shields.io/packagist/dt/hoa/regex.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/regex.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Regex

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Regex)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/regex)

This library provides tools to analyze regular expressions and generate strings
based on regular expressions ([Perl Compatible Regular
Expressions](http://pcre.org)).

[Learn more](https://central.hoa-project.net/Documentation/Library/Regex).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/regex`](https://packagist.org/packages/hoa/regex):

```sh
$ composer require hoa/regex '~1.0'
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

As a quick overview, we propose to see two examples. First, analyze a regular
expression, i.e. lex, parse and produce an AST. Second, generate strings based
on a regular expression by visiting its AST with an isotropic random approach.

### Analyze regular expressions

We need the [`Hoa\Compiler`
library](https://central.hoa-project.net/Resource/Library/Compiler) to lex, parse
and produce an AST of the following regular expression: `ab(c|d){2,4}e?`. Thus:

```php
// 1. Read the grammar.
$grammar  = new Hoa\File\Read('hoa://Library/Regex/Grammar.pp');

// 2. Load the compiler.
$compiler = Hoa\Compiler\Llk\Llk::load($grammar);

// 3. Lex, parse and produce the AST.
$ast      = $compiler->parse('ab(c|d){2,4}e?');

// 4. Dump the result.
$dump     = new Hoa\Compiler\Visitor\Dump();
echo $dump->visit($ast);

/**
 * Will output:
 *     >  #expression
 *     >  >  #concatenation
 *     >  >  >  token(literal, a)
 *     >  >  >  token(literal, b)
 *     >  >  >  #quantification
 *     >  >  >  >  #alternation
 *     >  >  >  >  >  token(literal, c)
 *     >  >  >  >  >  token(literal, d)
 *     >  >  >  >  token(n_to_m, {2,4})
 *     >  >  >  #quantification
 *     >  >  >  >  token(literal, e)
 *     >  >  >  >  token(zero_or_one, ?)
 */
```

We read that the whole expression is composed of a single concatenation of two
tokens: `a` and `b`, followed by a quantification, followed by another
quantification. The first quantification is an alternation of (a choice betwen)
two tokens: `c` and `d`, between 2 to 4 times. The second quantification is the
`e` token that can appear zero or one time.

We can visit the tree with the help of the [`Hoa\Visitor`
library](https://central.hoa-project.net/Resource/Library/Visitor).

### Generate strings based on regular expressions

To generate strings based on the AST of a regular expressions, we will use the
`Hoa\Regex\Visitor\Isotropic` visitor:

```php
$generator = new Hoa\Regex\Visitor\Isotropic(new Hoa\Math\Sampler\Random());
echo $generator->visit($ast);

/**
 * Could output:
 *     abdcde
 */
```

Strings are generated at random and match the given regular expression.

## Documentation

The
[hack book of `Hoa\Regex`](https://central.hoa-project.net/Documentation/Library/Regex)
contains detailed information about how to use this library and how it works.

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
