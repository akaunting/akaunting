# Traitor
[![StyleCI](https://styleci.io/repos/60994435/shield?style=flat)](https://styleci.io/repos/60994435)
[![Build Status](https://travis-ci.org/KKSzymanowski/Traitor.svg?branch=master)](https://travis-ci.org/KKSzymanowski/Traitor)
[![Latest Stable Version](https://poser.pugx.org/kkszymanowski/traitor/v/stable)](https://packagist.org/packages/kkszymanowski/traitor)
[![License](https://poser.pugx.org/kkszymanowski/traitor/license)](https://packagist.org/packages/kkszymanowski/traitor)

A PHP package for automatically adding a `trait use statement` to a given class.

## Installation
Via composer:
```
composer require kkszymanowski/traitor
```

## Usage
- Basic usage:
```
use Traitor\Traitor;

Traitor::addTrait(FooTrait::class)->toClass(FooClass:class);
```
- Add multiple traits:
```php
use Traitor\Traitor;

Traitor::addTraits([
    FooTrait::class,
    BarTrait::class,
    BazTrait::class
])->toClass(FooClass:class);

//or

Traitor::addTrait(FooTrait::class)
       ->addTrait(BarTrait::class)
       ->addTrait(BazTrait::class)
       ->toClass(FooClass:class);
```
- Check if class already uses trait:
```php
use Traitor\Traitor;

$alreadyUses = Traitor::alreadyUses(FooClass::class, BarTrait::class);
```
- Only generate output without changing files:
```php
use Traitor\Handlers\AbstractTreeHandler;

$handler = new AbstractTreeHandler(file($originalFilePath), FooTrait::class, BarClass::class);

$newContent = $handler->handle()->toString();
```
Note, that `AbstractTreeHandler` accepts input file as an array of lines, such as one produced from `file()` call.

## Behavior
Adding a new trait use statement does not change in any way formatting of your file(or at least it shouldn't).

If the trait is not present in the `use` section below the namespace declaration, it will be also added there, below any existing imports.

If it's not present in the `use` section in the class body, it will be added there above first existing use statement, on it's own line:
```
use Bar\PreviouslyExistingTrait;
use Baz\NewlyAddedTrait; // Here

class Foo
{
    use NewlyAddedTrait; // And here
    use PreviouslyExistingTrait;
}
```

## To do
- Add trait correctly when the class is empty, for example:
```
class Foo {}
```

- Add trait correctly when the class isn't namespaced.

