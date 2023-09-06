<?php

namespace Akaunting\Menu;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/menu.php' => config_path('menu.php'),
            __DIR__ . '/Resources/views' => base_path('resources/views/vendor/akaunting/menu'),
        ], 'menu');

        $this->app->singleton('menu', function ($app) {
            return new Menu($app['view'], $app['config']);
        });

        if (file_exists($file = app_path('Support/menus.php'))) {
            require_once($file);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlPackage();

        $this->mergeConfigFrom(__DIR__ . '/Config/menu.php', 'menu');

        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'menu');
    }

    /**
     * Register "iluminate/html" package.
     */
    private function registerHtmlPackage()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Collective\Html\HtmlFacade',
            'Form' => 'Collective\Html\FormFacade',
        ];

        AliasLoader::getInstance($aliases)->register();
    }
}
