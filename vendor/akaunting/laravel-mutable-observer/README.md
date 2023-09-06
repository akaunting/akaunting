# Mutable observer package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-mutable-observer)
![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/laravel-mutable-observer/tests.yml?label=tests)
[![StyleCI](https://github.styleci.io/repos/462492001/shield?style=flat&branch=master)](https://styleci.io/repos/462492001)
[![License](https://img.shields.io/github/license/akaunting/laravel-mutable-observer)](LICENSE.md)

This package allows you to `mute` and `unmute` a specific observer at will. It ships with a trait that adds mutable methods to your observer.

## Installation

Run the following command:

```bash
composer require akaunting/laravel-mutable-observer
```

## Usage

All you have to do is use the `Mutable` trait inside your observer.

```php
namespace App\Observers;

use Akaunting\MutableObserver\Traits\Mutable;

class UserObserver
{
    use Mutable;
}
```

Now you can `mute` and `unmute` the observer as needed:

```php
UserObserver::mute();

// Some logic, i.e. test

UserObserver::unmute();
```

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

Please review [our security policy](https://github.com/akaunting/laravel-sortable/security/policy) on how to report security vulnerabilities.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Stephen Lewis](https://github.com/monooso)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
