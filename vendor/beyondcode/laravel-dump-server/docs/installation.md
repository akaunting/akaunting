---
title: Installation
order: 1
---
# Laravel Dump Server

Bringing the Symfony Var-Dump Server to Laravel.

This package will give you a dump server, that collects all your dump call outputs, so that it does not interfere with HTTP / API responses.

![Dump Server Demo](/img/example.gif)

# Installation

You can install the package via composer:

```bash
composer require --dev beyondcode/laravel-dump-server
```

The package will register itself automatically. 

Optionally you can publish the package configuration using:

```bash
php artisan vendor:publish --provider="BeyondCode\DumpServer\DumpServerServiceProvider"
```

This will publish a file called `debug-server.php` in your `config` folder.
In the config file, you can specify the dump server host that you want to listen on, in case you want to change the default value.
