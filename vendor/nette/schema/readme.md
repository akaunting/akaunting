Nette Schema
************

[![Downloads this Month](https://img.shields.io/packagist/dm/nette/schema.svg)](https://packagist.org/packages/nette/schema)
[![Tests](https://github.com/nette/schema/workflows/Tests/badge.svg?branch=master)](https://github.com/nette/schema/actions)
[![Coverage Status](https://coveralls.io/repos/github/nette/schema/badge.svg?branch=master)](https://coveralls.io/github/nette/schema?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nette/schema/v/stable)](https://github.com/nette/schema/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/nette/schema/blob/master/license.md)


Introduction
============

A practical library for validation and normalization of data structures against a given schema with a smart & easy-to-understand API.

Documentation can be found on the [website](https://doc.nette.org/schema).

Installation:

```shell
composer require nette/schema
```

It requires PHP version 7.1 and supports PHP up to 8.3.


[Support Me](https://github.com/sponsors/dg)
--------------------------------------------

Do you like Nette Schema? Are you looking forward to the new features?

[![Buy me a coffee](https://files.nette.org/icons/donation-3.svg)](https://github.com/sponsors/dg)

Thank you!


Basic Usage
-----------

In variable `$schema` we have a validation schema (what exactly this means and how to create it we will say later) and in variable `$data` we have a data structure that we want to validate and normalize. This can be, for example, data sent by the user through an API, configuration file, etc.

The task is handled by the [Nette\Schema\Processor](https://api.nette.org/3.0/Nette/Schema/Processor.html) class, which processes the input and either returns normalized data or throws an [Nette\Schema\ValidationException](https://api.nette.org/3.0/Nette/Schema/ValidationException.html) exception on error.

```php
$processor = new Nette\Schema\Processor;

try {
	$normalized = $processor->process($schema, $data);
} catch (Nette\Schema\ValidationException $e) {
	echo 'Data is invalid: ' . $e->getMessage();
}
```

Method `$e->getMessages()` returns array of all message strings and `$e->getMessageObjects()` return all messages as [Nette\Schema\Message](https://api.nette.org/3.1/Nette/Schema/Message.html) objects.


Defining Schema
---------------

And now let's create a schema. The class [Nette\Schema\Expect](https://api.nette.org/3.0/Nette/Schema/Expect.html) is used to define it, we actually define expectations of what the data should look like. Let's say that the input data must be a structure (e.g. an array) containing elements `processRefund` of type bool and `refundAmount` of type int.

```php
use Nette\Schema\Expect;

$schema = Expect::structure([
    'processRefund' => Expect::bool(),
    'refundAmount' => Expect::int(),
]);
```

We believe that the schema definition looks clear, even if you see it for the very first time.

Lets send the following data for validation:

```php
$data = [
    'processRefund' => true,
    'refundAmount' => 17,
];

$normalized = $processor->process($schema, $data); // OK, it passes
```

The output, i.e. the value `$normalized`, is the object `stdClass`. If we want the output to be an array, we add a cast to schema `Expect::structure([...])->castTo('array')`.

All elements of the structure are optional and have a default value `null`. Example:

```php
$data = [
    'refundAmount' => 17,
];

$normalized = $processor->process($schema, $data); // OK, it passes
// $normalized = {'processRefund' => null, 'refundAmount' => 17}
```

The fact that the default value is `null` does not mean that it would be accepted in the input data `'processRefund' => null`. No, the input must be boolean, i.e. only `true` or `false`. We would have to explicitly allow `null` via `Expect::bool()->nullable()`.

An item can be made mandatory using `Expect::bool()->required()`. We change the default value to `false` using `Expect::bool()->default(false)` or shortly using `Expect::bool(false)`.

And what if we wanted to accept `1` and `0` besides booleans? Then we list the allowed values, which we will also normalize to boolean:

```php
$schema = Expect::structure([
    'processRefund' => Expect::anyOf(true, false, 1, 0)->castTo('bool'),
    'refundAmount' => Expect::int(),
]);

$normalized = $processor->process($schema, $data);
is_bool($normalized->processRefund); // true
```

Now you know the basics of how the schema is defined and how the individual elements of the structure behave. We will now show what all the other elements can be used in defining a schema.



Data Types: type()
------------------

All standard PHP data types can be listed in the schema:

```php
Expect::string($default = null)
Expect::int($default = null)
Expect::float($default = null)
Expect::bool($default = null)
Expect::null()
Expect::array($default = [])
```

And then all types [supported by the Validators](https://doc.nette.org/validators#toc-validation-rules) via `Expect::type('scalar')` or abbreviated `Expect::scalar()`. Also class or interface names are accepted, e.g. `Expect::type('AddressEntity')`.

You can also use union notation:

```php
Expect::type('bool|string|array')
```

The default value is always `null` except for `array` and `list`, where it is an empty array. (A list is an array indexed in ascending order of numeric keys from zero, that is, a non-associative array).


Array of Values: arrayOf() listOf()
-----------------------------------

The array is too general structure, it is more useful to specify exactly what elements it can contain. For example, an array whose elements can only be strings:

```php
$schema = Expect::arrayOf('string');

$processor->process($schema, ['hello', 'world']); // OK
$processor->process($schema, ['a' => 'hello', 'b' => 'world']); // OK
$processor->process($schema, ['key' => 123]); // ERROR: 123 is not a string
```

The list is an indexed array:

```php
$schema = Expect::listOf('string');

$processor->process($schema, ['a', 'b']); // OK
$processor->process($schema, ['a', 123]); // ERROR: 123 is not a string
$processor->process($schema, ['key' => 'a']); // ERROR: is not a list
$processor->process($schema, [1 => 'a', 0 => 'b']); // ERROR: is not a list
```

The parameter can also be a schema, so we can write:

```php
Expect::arrayOf(Expect::bool())
```

The default value is an empty array. If you specify default value, it will be merged with the passed data. This can be disabled using `mergeDefaults(false)`.


Enumeration: anyOf()
--------------------

`anyOf()` is a set of values ​​or schemas that a value can be. Here's how to write an array of elements that can be either `'a'`, `true`, or `null`:

```php
$schema = Expect::listOf(
	Expect::anyOf('a', true, null)
);

$processor->process($schema, ['a', true, null, 'a']); // OK
$processor->process($schema, ['a', false]); // ERROR: false does not belong there
```

The enumeration elements can also be schemas:

```php
$schema = Expect::listOf(
	Expect::anyOf(Expect::string(), true, null)
);

$processor->process($schema, ['foo', true, null, 'bar']); // OK
$processor->process($schema, [123]); // ERROR
```

The default value is `null`.


Structures
----------

Structures are objects with defined keys. Each of these key => value pairs is referred to as a "property":

Structures accept arrays and objects and return objects `stdClass` (unless you change it with `castTo('array')`, etc.).

By default, all properties are optional and have a default value of `null`. You can define mandatory properties using `required()`:

```php
$schema = Expect::structure([
	'required' => Expect::string()->required(),
	'optional' => Expect::string(), // the default value is null
]);

$processor->process($schema, ['optional' => '']);
// ERROR: item 'required' is missing

$processor->process($schema, ['required' => 'foo']);
// OK, returns {'required' => 'foo', 'optional' => null}
```

Although `null` is the default value of the `optional` property, it is not allowed in the input data (the value must be a string). Properties accepting `null` are defined using `nullable()`:

```php
$schema = Expect::structure([
	'optional' => Expect::string(),
	'nullable' => Expect::string()->nullable(),
]);

$processor->process($schema, ['optional' => null]);
// ERROR: 'optional' expects to be string, null given.

$processor->process($schema, ['nullable' => null]);
// OK, returns {'optional' => null, 'nullable' => null}
```

By default, there can be no extra items in the input data:

```php
$schema = Expect::structure([
	'key' => Expect::string(),
]);

$processor->process($schema, ['additional' => 1]);
// ERROR: Unexpected item 'additional'
```

Which we can change with `otherItems()`. As a parameter, we will specify the schema for each extra element:

```php
$schema = Expect::structure([
	'key' => Expect::string(),
])->otherItems(Expect::int());

$processor->process($schema, ['additional' => 1]); // OK
$processor->process($schema, ['additional' => true]); // ERROR
```

Deprecations
------------

You can deprecate property using the `deprecated([string $message])` method. Deprecation notices are returned by `$processor->getWarnings()` (since v1.1):

```php
$schema = Expect::structure([
	'old' => Expect::int()->deprecated('The item %path% is deprecated'),
]);

$processor->process($schema, ['old' => 1]); // OK
$processor->getWarnings(); // ["The item 'old' is deprecated"]
```

Ranges: min() max()
-------------------

Use `min()` and `max()` to limit the number of elements for arrays:

```php
// array, at least 10 items, maximum 20 items
Expect::array()->min(10)->max(20);
```

For strings, limit their length:

```php
// string, at least 10 characters long, maximum 20 characters
Expect::string()->min(10)->max(20);
```

For numbers, limit their value:

```php
// integer, between 10 and 20 inclusive
Expect::int()->min(10)->max(20);
```

Of course, it is possible to mention only `min()`, or only `max()`:

```php
// string, maximum 20 characters
Expect::string()->max(20);
```


Regular Expressions: pattern()
------------------------------

Using `pattern()`, you can specify a regular expression which the **whole** input string must match (i.e. as if it were wrapped in characters `^` a `$`):

```php
// just 9 digits
Expect::string()->pattern('\d{9}');
```


Custom Assertions: assert()
---------------------------

You can add any other restrictions using `assert(callable $fn)`.

```php
$countIsEven = function ($v) { return count($v) % 2 === 0; };

$schema = Expect::arrayOf('string')
	->assert($countIsEven); // the count must be even

$processor->process($schema, ['a', 'b']); // OK
$processor->process($schema, ['a', 'b', 'c']); // ERROR: 3 is not even
```

Or

```php
Expect::string()->assert('is_file'); // the file must exist
```

You can add your own description for each assertions. It will be part of the error message.

```php
$schema = Expect::arrayOf('string')
	->assert($countIsEven, 'Even items in array');

$processor->process($schema, ['a', 'b', 'c']);
// Failed assertion "Even items in array" for item with value array.
```

The method can be called repeatedly to add more assertions.


Mapping to Objects: from()
--------------------------

You can generate structure schema from the class. Example:

```php
class Config
{
	/** @var string */
	public $name;
	/** @var string|null */
	public $password;
	/** @var bool */
	public $admin = false;
}

$schema = Expect::from(new Config);

$data = [
	'name' => 'jeff',
];

$normalized = $processor->process($schema, $data);
// $normalized instanceof Config
// $normalized = {'name' => 'jeff', 'password' => null, 'admin' => false}
```

If you are using PHP 7.4 or higher, you can use native types:

```php
class Config
{
	public string $name;
	public ?string $password;
	public bool $admin = false;
}

$schema = Expect::from(new Config);
```

Anonymous classes are also supported:

```php
$schema = Expect::from(new class {
	public string $name;
	public ?string $password;
	public bool $admin = false;
});
```

Because the information obtained from the class definition may not be sufficient, you can add a custom schema for the elements with the second parameter:

```php
$schema = Expect::from(new Config, [
	'name' => Expect::string()->pattern('\w:.*'),
]);
```


Casting: castTo()
-----------------

Successfully validated data can be cast:

```php
Expect::scalar()->castTo('string');
```

In addition to native PHP types, you can also cast to classes:

```php
Expect::scalar()->castTo('AddressEntity');
```


Normalization: before()
-----------------------

Prior to the validation itself, the data can be normalized using the method `before()`. As an example, let's have an element that must be an array of strings (eg `['a', 'b', 'c']`), but receives input in the form of a string `a b c`:

```php
$explode = function ($v) { return explode(' ', $v); };

$schema = Expect::arrayOf('string')
	->before($explode);

$normalized = $processor->process($schema, 'a b c');
// OK, returns ['a', 'b', 'c']
```
