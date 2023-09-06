# Web Application Firewall (WAF) package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-firewall)
![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/laravel-firewall/tests.yml?label=tests)
[![StyleCI](https://github.styleci.io/repos/197242392/shield?style=flat&branch=master)](https://styleci.io/repos/197242392)
[![License](https://img.shields.io/github/license/akaunting/laravel-firewall)](LICENSE.md)

This package intends to protect your Laravel app from different type of attacks such as XSS, SQLi, RFI, LFI, User Agent, and a lot more. It will also block repeated attacks and send notification via email and/or slack when attack is detected. Furthermore, it will log failed logins and block the IP after a number of attempts.

Note: Some middleware classes (i.e. Xss) are empty as the `Middleware` abstract class that they extend does all of the job, dynamically. In short, they all works ;)

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/laravel-firewall
```

### 2. Publish

Publish configuration, language, and migrations

```bash
php artisan vendor:publish --tag=firewall
```

### 3. Database

Create db tables

```bash
php artisan migrate
```

### 4. Configure

You can change the firewall settings of your app from `config/firewall.php` file

## Usage

Middlewares are already defined so should just add them to routes. The `firewall.all` middleware applies all the middlewares available in the `all_middleware` array of config file.

```php
Route::group(['middleware' => 'firewall.all'], function () {
    Route::get('/', 'HomeController@index');
});
```

You can apply each middleware per route. For example, you can allow only whitelisted IPs to access admin:

```php
Route::group(['middleware' => 'firewall.whitelist'], function () {
    Route::get('/admin', 'AdminController@index');
});
```

Or you can get notified when anyone NOT in `whitelist` access admin, by adding it to the `inspections` config:

```php
Route::group(['middleware' => 'firewall.url'], function () {
    Route::get('/admin', 'AdminController@index');
});
```

Available middlewares applicable to routes:

```php
firewall.all

firewall.agent
firewall.bot
firewall.geo
firewall.ip
firewall.lfi
firewall.php
firewall.referrer
firewall.rfi
firewall.session
firewall.sqli
firewall.swear
firewall.url
firewall.whitelist
firewall.xss
```

You may also define `routes` for each middleware in `config/firewall.php` and apply that middleware or `firewall.all` at the top of all routes.

## Notifications

Firewall will send a notification as soon as an attack has been detected. Emails entered in `notifications.email.to` config must be valid Laravel users in order to send notifications. Check out the Notifications documentation of Laravel for further information.

## Changelog

Please see [Releases](../../releases) for more information on what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

Please review [our security policy](https://github.com/akaunting/laravel-firewall/security/policy) on how to report security vulnerabilities.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
