# Language switcher package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-language)
[![StyleCI](https://github.styleci.io/repos/102290249/shield?style=flat&branch=master)](https://styleci.io/repos/102290249)
[![Quality](https://img.shields.io/scrutinizer/quality/g/akaunting/laravel-language?label=quality)](https://scrutinizer-ci.com/g/akaunting/laravel-language)
[![License](https://img.shields.io/github/license/akaunting/laravel-language)](LICENSE.md)

This package allows switching locale easily on Laravel projects. It's so simple to use, once it's installed, your App locale will change only by passing routes into SetLanguage middleware.

**Top features:**

- Change automatically app locale depending on user browser configuration
- Language flags built-in for easy implementation
- Get language name like 'English' or 'Español' from codes such as 'en' or 'es'
- Option to choose short (en) or long (en-GB) language code
- Store locale on users table
- Restrict users to set languages you don't have translations
- Helper functions for clean, simple and easy to read API
- Supports Carbon and Date packages

## Getting Started

### 1. Install

Run the following command:

```
composer require akaunting/laravel-language
```

### 2. Register (for Laravel < 5.5)

Register the service provider in ``config/app.php``

```php
Akaunting\Language\Provider::class,
```

Add alias if you want to use the facade.

```php
'Language'   => Akaunting\Language\Facade::class,
```

### 3. Publish

Publish config, migration and blade files.

```
php artisan vendor:publish --tag=language
```

### 4. Migrate


Add locale column to users table:

```
php artisan migrate
```


### 5. Configure

Default values can be modified also on `config/language.php`

#### Keys

- route: Makes route available
- carbon: Sets briannesbitt/carbon translator language
- date: Sets jenssegers/date translator language
- home: Make home route available
- auto: Sets language automatically depending on user's browser config
- prefix: Prefix of routes URI to set locale
- middleware: default middleware to set locale
- controller: default controller to handle locale
- flags: Settings such as width, class etc for flags
- mode: The language code and name mode
- allowed: Allowed language codes
- all: Available language names and codes

## Usage

### Middleware

All routes in which you want to set language should be under the `language`
middleware to set at each request to App locale.

```php
Route::group(['middleware' => 'language'], function () {

    // Here your routes

});
```

### URL

- Via URL with return home: /languages/{locale}/home
- Via URL with return back: /languages/{locale}/back

**Tip:** */languages prefix can be changed from ```config/language.php```*

## Methods

### language()->allowed()

Returns an array with ```[$code => $name]``` for all allowed
languages of config. Example usage on blade:

```php
@foreach (language()->allowed() as $code => $name)
    <a href="{{ language()->back($code) }}">{{ $name }}</a>
@endforeach
```

### language()->flags()

Returns an output with flags for all allowed languages of config.
Output can be changed from ```resources/views/vendor/language``` folder

### language()->flag()

Returns the flag of the current locale.
Output can be changed from ```resources/views/vendor/language``` folder

### language()->names($codes = null)

Get an array like ```[$code => $name]``` from an array of only $codes.


### language()->codes($langs = null)

Get an array like ```[$name => $code]``` from an array of only $langs.

### language()->back($code)

Returns the URL to set up language and return back: ```back()```

Also if you prefer to use directly route() function you can use it
as following code:

```php
{{ route('language::back', ['locale' => $code]) }}
```

### language()->home($code)

Returns the URL to set language and return to home: ```url('/')```

Also if you prefer to use directly route() function you can use it
as following code:

```php
{{ route('language::home', ['locale' => $code]) }}
```

### language()->getName($code = 'default')

Returns the language name of ```$code``` if specified or the current
language set if not.

**Tip:** *Use app()->getLocale() to get the current locale*

### language()->getCode($name = 'default')

Returns the language code of ```$name``` if specified or the current
language set if not.

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
