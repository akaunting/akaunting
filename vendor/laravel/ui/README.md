# Laravel UI

<a href="https://packagist.org/packages/laravel/ui"><img src="https://img.shields.io/packagist/dt/laravel/ui" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/ui"><img src="https://img.shields.io/packagist/v/laravel/ui" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/ui"><img src="https://img.shields.io/packagist/l/laravel/ui" alt="License"></a>

## This legacy package is a very simple authentication scaffolding built on the Bootstrap CSS framework. While it continues to work with the latest version of Laravel, you should consider using [Laravel Breeze](https://github.com/laravel/breeze) for new projects. Or, for something more robust, consider [Laravel Jetstream](https://github.com/laravel/jetstream).

## Introduction

While Laravel does not dictate which JavaScript or CSS pre-processors you use, it does provide a basic starting point using [Bootstrap](https://getbootstrap.com/), [React](https://reactjs.org/), and / or [Vue](https://vuejs.org/) that will be helpful for many applications. By default, Laravel uses [NPM](https://www.npmjs.org/) to install both of these frontend packages.

- [Official Documentation](#official-documentation)
    - [Supported Versions](#supported-versions)
    - [Installation](#installation)
        - [CSS](#css)
        - [JavaScript](#javascript)
    - [Writing CSS](#writing-css)
    - [Writing JavaScript](#writing-javascript)
        - [Writing Vue Components](#writing-vue-components)
        - [Using React](#using-react)
    - [Adding Presets](#adding-presets)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [Security Vulnerabilities](#security-vulnerabilities)
- [License](#license)

## Official Documentation

### Supported Versions

Only the latest major version of Laravel UI receives bug fixes. The table below lists compatible Laravel versions:

| Version | Laravel Version |
|---- |----|
| [1.x](https://github.com/laravel/ui/tree/1.x) | 5.8, 6.x |
| [2.x](https://github.com/laravel/ui/tree/2.x) | 7.x |
| [3.x](https://github.com/laravel/ui/tree/3.x) | 8.x |

### Installation

The Bootstrap and Vue scaffolding provided by Laravel is located in the `laravel/ui` Composer package, which may be installed using Composer:

```bash
composer require laravel/ui
```

Once the `laravel/ui` package has been installed, you may install the frontend scaffolding using the `ui` Artisan command:

```bash
// Generate basic scaffolding...
php artisan ui bootstrap
php artisan ui vue
php artisan ui react

// Generate login / registration scaffolding...
php artisan ui bootstrap --auth
php artisan ui vue --auth
php artisan ui react --auth
```

#### CSS

[Laravel Mix](https://laravel.com/docs/mix) provides a clean, expressive API over compiling SASS or Less, which are extensions of plain CSS that add variables, mixins, and other powerful features that make working with CSS much more enjoyable. In this document, we will briefly discuss CSS compilation in general; however, you should consult the full [Laravel Mix documentation](https://laravel.com/docs/mix) for more information on compiling SASS or Less.

#### JavaScript

Laravel does not require you to use a specific JavaScript framework or library to build your applications. In fact, you don't have to use JavaScript at all. However, Laravel does include some basic scaffolding to make it easier to get started writing modern JavaScript using the [Vue](https://vuejs.org) library. Vue provides an expressive API for building robust JavaScript applications using components. As with CSS, we may use Laravel Mix to easily compile JavaScript components into a single, browser-ready JavaScript file.

### Writing CSS

After installing the `laravel/ui` Composer package and [generating the frontend scaffolding](#introduction), Laravel's `package.json` file will include the `bootstrap` package to help you get started prototyping your application's frontend using Bootstrap. However, feel free to add or remove packages from the `package.json` file as needed for your own application. You are not required to use the Bootstrap framework to build your Laravel application - it is provided as a good starting point for those who choose to use it.

Before compiling your CSS, install your project's frontend dependencies using the [Node package manager (NPM)](https://www.npmjs.org):

```bash
npm install
```

Once the dependencies have been installed using `npm install`, you can compile your SASS files to plain CSS using [Laravel Mix](https://laravel.com/docs/mix#working-with-stylesheets). The `npm run dev` command will process the instructions in your `webpack.mix.js` file. Typically, your compiled CSS will be placed in the `public/css` directory:

```bash
npm run dev
```

The `webpack.mix.js` file included with Laravel's frontend scaffolding will compile the `resources/sass/app.scss` SASS file. This `app.scss` file imports a file of SASS variables and loads Bootstrap, which provides a good starting point for most applications. Feel free to customize the `app.scss` file however you wish or even use an entirely different pre-processor by [configuring Laravel Mix](https://laravel.com/docs/mix).

### Writing JavaScript

All of the JavaScript dependencies required by your application can be found in the `package.json` file in the project's root directory. This file is similar to a `composer.json` file except it specifies JavaScript dependencies instead of PHP dependencies. You can install these dependencies using the [Node package manager (NPM)](https://www.npmjs.org):

```bash
npm install
```

> By default, the Laravel `package.json` file includes a few packages such as `lodash` and `axios` to help you get started building your JavaScript application. Feel free to add or remove from the `package.json` file as needed for your own application.

Once the packages are installed, you can use the `npm run dev` command to [compile your assets](https://laravel.com/docs/mix). Webpack is a module bundler for modern JavaScript applications. When you run the `npm run dev` command, Webpack will execute the instructions in your `webpack.mix.js` file:

```bash
npm run dev
```

By default, the Laravel `webpack.mix.js` file compiles your SASS and the `resources/js/app.js` file. Within the `app.js` file you may register your Vue components or, if you prefer a different framework, configure your own JavaScript application. Your compiled JavaScript will typically be placed in the `public/js` directory.

> The `app.js` file will load the `resources/js/bootstrap.js` file which bootstraps and configures Vue, Axios, jQuery, and all other JavaScript dependencies. If you have additional JavaScript dependencies to configure, you may do so in this file.

#### Writing Vue Components

When using the `laravel/ui` package to scaffold your frontend, an `ExampleComponent.vue` Vue component will be placed in the `resources/js/components` directory. The `ExampleComponent.vue` file is an example of a [single file Vue component](https://vuejs.org/guide/single-file-components) which defines its JavaScript and HTML template in the same file. Single file components provide a very convenient approach to building JavaScript driven applications. The example component is registered in your `app.js` file:

```javascript
Vue.component(
    'example-component',
    require('./components/ExampleComponent.vue').default
);
```

To use the component in your application, you may drop it into one of your HTML templates. For example, after running the `php artisan ui vue --auth` Artisan command to scaffold your application's authentication and registration screens, you could drop the component into the `home.blade.php` Blade template:

```blade
@extends('layouts.app')

@section('content')
    <example-component></example-component>
@endsection
```

> Remember, you should run the `npm run dev` command each time you change a Vue component. Or, you may run the `npm run watch` command to monitor and automatically recompile your components each time they are modified.

If you are interested in learning more about writing Vue components, you should read the [Vue documentation](https://vuejs.org/guide/), which provides a thorough, easy-to-read overview of the entire Vue framework.

#### Using React

If you prefer to use React to build your JavaScript application, Laravel makes it a cinch to swap the Vue scaffolding with React scaffolding:

```bash
composer require laravel/ui

// Generate basic scaffolding...
php artisan ui react

// Generate login / registration scaffolding...
php artisan ui react --auth
````

### Adding Presets

Presets are "macroable", which allows you to add additional methods to the `UiCommand` class at runtime. For example, the following code adds a `nextjs` method to the `UiCommand` class. Typically, you should declare preset macros in a [service provider](https://laravel.com/docs/providers):

```php
use Laravel\Ui\UiCommand;

UiCommand::macro('nextjs', function (UiCommand $command) {
    // Scaffold your frontend...
});
```
Then, you may call the new preset via the `ui` command:

```bash
php artisan ui nextjs
```

## Contributing

Thank you for considering contributing to UI! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/laravel/ui/security/policy) on how to report security vulnerabilities.

## License

Laravel UI is open-sourced software licensed under the [MIT license](LICENSE.md).
