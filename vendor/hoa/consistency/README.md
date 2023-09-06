<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/Consistency"><img src="https://img.shields.io/travis/hoaproject/Consistency/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/Consistency?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/Consistency/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/consistency"><img src="https://img.shields.io/packagist/dt/hoa/consistency.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/consistency.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Consistency

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Consistency)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/consistency)

This library provides a thin layer between PHP VMs and libraries to ensure
consistency accross VM versions and library versions.

[Learn more](https://central.hoa-project.net/Documentation/Library/Consistency).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/consistency`](https://packagist.org/packages/hoa/consistency):

```sh
$ composer require hoa/consistency '~1.0'
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

We propose a quick overview of how the consistency API ensures foreward and
backward compatibility, also an overview of the [PSR-4
autoloader](http://www.php-fig.org/psr/psr-4/) and the xcallable API.

### Foreward and backward compatibility

The `Hoa\Consistency\Consistency` class ensures foreward and backward
compatibility.

#### Example with keywords

The `Hoa\Consistency\Consistency::isKeyword` checks whether a specific word is
reserved by PHP or not. Let's say your current PHP version does not support the
`callable` keyword or type declarations such as `int`, `float`, `string` etc.,
the `isKeyword` method will tell you if they are reserved keywords: Not only
for your current PHP version, but maybe in an incoming version.

```php
$isKeyword = Hoa\Consistency\Consistency::isKeyword('yield');
```

It avoids to write algorithms that might break in the future or for your users
living on the edge.

#### Example with identifiers

PHP identifiers are defined by a regular expression. It might change in the
future. To prevent breaking your algorithms, you can use the
`Hoa\Consistency\Consistency::isIdentifier` method to check an identifier is
correct regarding current PHP version:

```php
$isValidIdentifier = Hoa\Consistency\Consistency::isIdentifier('foo');
```

#### Flexible entities

Flexible entities are very simple. If we declare `Foo\Bar\Bar` as a flexible
entity, we will be able to access it with the `Foo\Bar\Bar` name or `Foo\Bar`.
This is very useful if your architecture evolves but you want to keep the
backward compatibility. For instance, it often happens that you create a
`Foo\Bar\Exception` class in the `Foo/Bar/Exception.php` file. But after few
versions, you realise other exceptions need to be introduced, so you need an
`Exception` directory. In this case, `Foo\Bar\Exception` should move as
`Foo\Bar\Exception\Exception`. If this latter is declared as a flexible entity,
backward compatibility will be kept.

```php
Hoa\Consistency\Consistency::flexEntity('Foo\Bar\Exception\Exception');
```

Another example is the “entry-class” (informal naming).
`Hoa\Consistency\Consistency` is a good example. This is more convenient to
write `Hoa\Consistency` instead of `Hoa\Consistency\Consistency`. This is
possible because this is a flexible entity.

#### Throwable & co.

The `Throwable` interface has been introduced to represent a whole new exception
architecture in PHP. Thus, to be compatible with incoming PHP versions, you
might want to use this interface in some cases. Hopefully, the `Throwable`
interface will be created for you if it does not exists.

```php
try {
    …
} catch (Throwable $e) {
    …
}
```

### Autoloader

`Hoa\Consistency\Autoloader` is a [PSR-4
compatible](http://www.php-fig.org/psr/psr-4/) autoloader. It simply works as
follows:
  * `addNamespace` is used to map a namespace prefix to a directory,
  * `register` is used to register the autoloader.

The API also provides the `load` method to force the load of an entity,
`unregister` to unregister the autoloader, `getRegisteredAutoloaders` to get
a list of all registered autoloaders etc.

For instance, to map the `Foo\Bar` namespace to the `Source/` directory:

```php
$autoloader = new Hoa\Consistency\Autoloader();
$autoloader->addNamespace('Foo\Bar', 'Source');
$autoloader->register();

$baz = new Foo\Bar\Baz(); // automatically loaded!
```

### Xcallable

Xcallables are “extended callables”. It is a unified API to invoke callables of
any kinds, and also extends some Hoa's API (like
[`Hoa\Event`](https://central.hoa-project.net/Resource/Library/Event)
or
[`Hoa\Stream`](https://central.hoa-project.net/Resource/Library/Stream)). It
understands the following kinds:
  * `'function'` as a string,
  * `'class::method'` as a string,
  * `'class', 'method'` as 2 string arguments,
  * `$object, 'method'` as 2 arguments,
  * `$object, ''` as 2 arguments, the “able” is unknown,
  * `function (…) { … }` as a closure,
  * `['class', 'method']` as an array of strings,
  * `[$object, 'method']` as an array.

To use it, simply instanciate the `Hoa\Consistency\Xcallable` class and use it
as a function:

```php
$xcallable = new Hoa\Consistency\Xcallable('strtoupper');
var_dump($xcallable('foo'));

/**
 * Will output:
 *     string(3) "FOO"
 */
```

The `Hoa\Consistency\Xcallable::distributeArguments` method invokes the callable
but the arguments are passed as an array:

```php
$xcallable->distributeArguments(['foo']);
```

This is also possible to get a unique hash of the callable:

```php
var_dump($xcallable->getHash());

/**
 * Will output:
 *     string(19) "function#strtoupper"
 */
```

Finally, this is possible to get a reflection instance of the current callable
(can be of kind [`ReflectionFunction`](http://php.net/ReflectionFunction),
[`ReflectionClass`](http://php.net/ReflectionClass),
[`ReflectionMethod`](http://php.net/ReflectionMethod) or
[`ReflectionObject`](http://php.net/ReflectionObject)):

```php
var_dump($xcallable->getReflection());

/**
 * Will output:
 *     object(ReflectionFunction)#42 (1) {
 *       ["name"]=>
 *       string(10) "strtoupper"
 *     }
 */
```

When the object is set but not the method, the latter will be deduced if
possible. If the object is of kind
[`Hoa\Stream`](http://central.hoa-project.net/Resource/Library/Stream), then
according to the type of the arguments given to the callable, the
`writeInteger`, `writeString`, `writeArray` etc. method will be used. If the
argument is of kind `Hoa\Event\Bucket`, then the method name will be deduced
based on the data contained inside the event bucket. This is very handy. For
instance, the following example will work seamlessly:

```php
Hoa\Event\Event::getEvent('hoa://Event/Exception')
    ->attach(new Hoa\File\Write('Exceptions.log'));
```

The `attach` method on `Hoa\Event\Event` transforms its argument as an
xcallable. In this particular case, the method to call is unknown, we only have
an object (of kind `Hoa\File\Write`). However, because this is a stream, the
method will be deduced according to the data contained in the event bucket fired
on the `hoa://Event/Exception` event channel.

## Documentation

The
[hack book of `Hoa\Consistency`](https://central.hoa-project.net/Documentation/Library/Consistency)
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
