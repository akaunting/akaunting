Upgrading
=========


## Laravel 5.5 to 5.6

Laravel 5.6 has a brand new Monolog-base logging system changing the integration
point for the Bugsnag handlers. The `Illuminate\Contracts\Logging\Log` class has
been removed in favor of `Illuminate\Log\LogManager`.

To upgrade, remove the existing Bugsnag logging integration from the `register`
method of `app/Providers/AppServiceProvider.php`:

```diff
- $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
- $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
```

Or if using the multi-logger:

```diff
- $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
- $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
```


Then add Bugsnag to your logging stack in `config/logging.php`:

```php
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            // Add bugsnag to the stack:
            'channels' => ['single', 'bugsnag'],
        ],

        // ...

        // Create a bugsnag logging channel:
        'bugsnag' => [
            'driver' => 'bugsnag',
        ],
    ],
```

References:
* The [bugsnag-laravel integration guide](https://docs.bugsnag.com/platforms/php/laravel/)
* Our [blog post about the new changes in Laravel 5.6](https://blog.bugsnag.com/laravel-5-6/)
* [Laravel 5.6 Logging documentation](https://laravel.com/docs/5.6/logging)


## 1.x to 2.x

*Our library has gone through some major improvements. The primary change to watch out for is we're no longer overriding your exception handler.*

Since we're no longer overriding your exception handler, you'll need to restore your original handler, and then see our [new integration guide](http://docs.bugsnag.com/platforms/php/laravel/) for how to bind our new logger to the container.

If you'd like access to all our new configuration, you'll need to re-publish our config file.
