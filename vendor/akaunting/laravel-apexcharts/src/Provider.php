<?php

namespace Akaunting\Apexcharts;

use Akaunting\Apexcharts\Chart;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class Provider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->alias(Chart::class, 'apexcharts');

        $this->mergeConfigFrom(__DIR__ . '/Config/apexcharts.php', 'apexcharts');
    }

    /**
     * When this method is apply we have all laravel providers and methods available
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'apexcharts');

        $this->publishes([
            __DIR__ . '/Config/apexcharts.php'  => config_path('apexcharts.php'),
            __DIR__ . '/Public'                 => public_path('vendor/apexcharts'),
            __DIR__ . '/Views'                  => resource_path('views/vendor/apexcharts'),
        ], 'apexcharts');

        $this->registerBladeDirectives();
    }

    public function registerBladeDirectives(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('apexchartsScripts', function ($expression) {
                return '{!! \Akaunting\Apexcharts\Chart::loadScript(' . $expression . ') !!}';
            });
        });
    }
}
