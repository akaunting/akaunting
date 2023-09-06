# Currency formatting and conversion package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-money)
![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/laravel-money/tests.yml?label=tests)
[![StyleCI](https://github.styleci.io/repos/112121508/shield?style=flat&branch=master)](https://styleci.io/repos/112121508)
[![License](https://img.shields.io/github/license/akaunting/laravel-money)](LICENSE.md)

This package intends to provide tools for formatting and conversion monetary values in an easy, yet powerful way for Laravel projects.

### Why not use the `moneyphp` package?

Because it uses the `intl` extension for number formatting. `intl` extension isn't present by default on PHP installs and can give [different results](http://moneyphp.org/en/latest/features/formatting.html#intl-formatter) in different servers.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/laravel-money
```

### 2. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=money
```

### 3. Configure

You can change the currencies information of your app from `config/money.php` file

## Usage

```php
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

echo Money::USD(500); // '$5.00' unconverted
echo new Money(500, new Currency('USD')); // '$5.00' unconverted
echo Money::USD(500, true); // '$500.00' converted
echo new Money(500, new Currency('USD'), true); // '$500.00' converted
```

### Advanced

```php
$m1 = Money::USD(500);
$m2 = Money::EUR(500);

$m1->getCurrency();
$m1->isSameCurrency($m2);
$m1->compare($m2);
$m1->equals($m2);
$m1->greaterThan($m2);
$m1->greaterThanOrEqual($m2);
$m1->lessThan($m2);
$m1->lessThanOrEqual($m2);
$m1->convert(Currency::GBP, 3.5);
$m1->add($m2);
$m1->subtract($m2);
$m1->multiply(2);
$m1->divide(2);
$m1->allocate([1, 1, 1]);
$m1->isZero();
$m1->isPositive();
$m1->isNegative();
$m1->format();
```

### Helpers

```php
money(500)
money(500, 'USD')
currency('USD')
```

### Blade Directives

```php
@money(500)
@money(500, 'USD')
@currency('USD')
```

### Blade Component

Same as the directive, there is also a `blade` component for you to create money and currency in your views:

```html
<x-money amount="500" />
or
<x-money amount="500" currency="USD" />
or
<x-money amount="500" currency="USD" convert />

<x-currency currency="USD" />
```

### Macros

This package implements the Laravel `Macroable` trait, allowing macros and mixins on both `Money` and `Currency`.

Example use case:

```php
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

Money::macro(
    'absolute',
    fn () => $this->isPositive() ? $this : $this->multiply(-1)
);

$money = Money::USD(1000)->multiply(-1);

$absolute = $money->absolute();
```

Macros can be called statically too:

```php
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

Money::macro('zero', fn (?string $currency = null) => new Money(0, new Currency($currency ?? 'GBP')));

$money = Money::zero();
```

### Mixins

Along with Macros, Mixins are also supported. This allows merging another classes methods into the Money or Currency class.

Define the mixin class:

```php
use Akaunting\Money\Money;

class CustomMoney 
{
    public function absolute(): Money
    {
        return $this->isPositive() ? $this : $this->multiply(-1);
    }
    
    public static function zero(?string $currency = null): Money
    {
        return new Money(0, new Currency($currency ?? 'GBP'));
    }
}
```

Register the mixin, by passing an instance of the class:

```php
Money::mixin(new CustomMoney);
```

The methods from the custom class will be available:

```php
$money = Money::USD(1000)->multiply(-1);
$absolute = $money->absolute();

// Static methods via mixins are supported too:
$money = Money::zero();
```

## Changelog

Please see [Releases](../../releases) for more information on what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

Please review [our security policy](https://github.com/akaunting/laravel-money/security/policy) on how to report security vulnerabilities.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
