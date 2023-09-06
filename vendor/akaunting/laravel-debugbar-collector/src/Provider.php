<?php

namespace Akaunting\DebugbarCollector;

use Akaunting\DebugbarCollector\AkauntingCollector;
use Akaunting\DebugbarCollector\ServerCollector;
use Barryvdh\Debugbar\LaravelDebugbar;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class Provider extends BaseServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->extend(LaravelDebugbar::class, function ($debugbar) {
            $debugbar->addCollector(new AkauntingCollector());
            $debugbar->addCollector(new ServerCollector());

            return $debugbar;
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }
}
