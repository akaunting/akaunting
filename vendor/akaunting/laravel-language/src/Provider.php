<?php

namespace Akaunting\Language;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\AgentServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Routes/web.php';
        }

        $this->publishes([
            __DIR__ . '/Config/language.php'                                  => config_path('language.php'),
            __DIR__ . '/Migrations/2020_01_01_000000_add_locale_column.php'   => database_path('migrations/2020_01_01_000000_add_locale_column.php'),
            __DIR__ . '/Resources/views/flag.blade.php'                       => resource_path('views/vendor/language/flag.blade.php'),
            __DIR__ . '/Resources/views/flags.blade.php'                      => resource_path('views/vendor/language/flags.blade.php'),
        ], 'language');

        $router->aliasMiddleware('language', config('language.middleware'));

        $this->app->register(AgentServiceProvider::class);

        $this->app->singleton('language', function ($app) {
            return new Language($app);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/language.php', 'language');
    }
}
