composer/class-map-generator
============================

Utilities to generate class maps and scan PHP code.

[![Continuous Integration](https://github.com/composer/class-map-generator/workflows/Continuous%20Integration/badge.svg?branch=main)](https://github.com/composer/class-map-generator/actions)


Installation
------------

Install the latest version with:

```bash
$ composer require composer/class-map-generator
```


Requirements
------------

* PHP 7.2 is required.


Basic usage
-----------

If all you want is to scan a directory and extract a classmap with all
classes/interfaces/traits/enums mapped to their paths, you can simply use:


```php
use Composer\ClassMapGenerator\ClassMapGenerator;

$map = ClassMapGenerator::createMap('path/to/scan');
foreach ($map as $symbol => $path) {
    // do your thing
}
```

For more advanced usage, you can instantiate a generator object and call scanPaths one or more time
then call getClassMap to get a ClassMap object containing the resulting map + eventual warnings.

```php
use Composer\ClassMapGenerator\ClassMapGenerator;

$generator = new ClassMapGenerator;
$generator->scanPaths('path/to/scan');
$generator->scanPaths('path/to/scan2');

$classMap = $generator->getClassMap();
$classMap->sort(); // optionally sort classes alphabetically
foreach ($classMap->getMap() as $symbol => $path) {
    // do your thing
}

foreach ($classMap->getAmbiguousClasses() as $symbol => $paths) {
    // warn user about ambiguous class resolution
}
```


License
-------

composer/class-map-generator is licensed under the MIT License, see the LICENSE file for details.
