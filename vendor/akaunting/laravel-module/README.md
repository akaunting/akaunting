# Module management package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-module)
[![StyleCI](https://github.styleci.io/repos/180859866/shield?style=flat&branch=master)](https://styleci.io/repos/180859866)
[![Quality](https://img.shields.io/scrutinizer/quality/g/akaunting/laravel-module?label=quality)](https://scrutinizer-ci.com/g/akaunting/laravel-module)
[![License](https://img.shields.io/github/license/akaunting/laravel-module)](LICENSE.md)

This package intends to make your Laravel app extensible via modules. A module is a kinda small Laravel app, shipping with its own views, controllers, models, etc.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/laravel-module
```

### 2. Register

Service provider and facade will be registered automatically. If you want to register them manually in `config/app.php`:

```php
Akaunting\Module\Facade::class,
Akaunting\Module\Providers\Laravel::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=module
```

### 4. Configure

You can change the configuration from `config/module.php` file

### 5. Autoloading

By default, the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

``` json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "modules/"
    }
  }
}
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Usage

Check out the [wiki](../../wiki) about the usage and further documentation.

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Nicolas Widart](https://github.com/nwidart)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
