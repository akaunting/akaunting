# Version management package for Laravel

[![Downloads](https://poser.pugx.org/akaunting/laravel-version/d/total.svg)](https://github.com/akaunting/laravel-version)
[![StyleCI](https://styleci.io/repos/101269981/shield?style=flat&branch=master)](https://styleci.io/repos/101269981)
[![Quality](https://scrutinizer-ci.com/g/akaunting/laravel-version/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/akaunting/laravel-version)
[![License](https://poser.pugx.org/akaunting/laravel-version/license.svg)](LICENSE.md)

This is a [SemVer](http://semver.org) compatible version management package for any software built on Laravel.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/laravel-version
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
Akaunting\Version\Provider::class,
```

Add alias if you want to use the facade.

```php
'Version' => Akaunting\Version\Facade::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=version
```


### 4. Configure

You can change the version information of your app from `config/version.php` file

## Usage

### version($method = null)

You can either enter the method like `version('short')` or leave it empty so you could firstly get the instance then call the methods like `version()->short()`

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.