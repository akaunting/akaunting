<?php

namespace ConsoleTVs\Charts;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ChartsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__.'/Config/charts.php' => config_path('charts.php'),
        ], 'charts_config');

        $this->loadViewsFrom(__DIR__.'/Views', 'charts');

        $this->publishes([
            __DIR__.'/Views' => resource_path('views/vendor/charts'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \ConsoleTVs\Charts\Commands\ChartsCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/charts.php',
            'charts'
        );
    }
}
