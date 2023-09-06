<?php

namespace Akaunting\MutableObserver;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProxyManager::class, function ($app) {
            return new ProxyManager($app);
        });

        $this->app->bind(Proxy::class, function ($app, $parameters) {
            return new Proxy($parameters['target'], $parameters['events']);
        });
    }
}
