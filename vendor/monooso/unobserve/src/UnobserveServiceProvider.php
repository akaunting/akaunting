<?php

namespace Monooso\Unobserve;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UnobserveServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(ProxyManager::class, function ($app) {
            return new ProxyManager($app);
        });

        $this->app->bind(Proxy::class, function ($app, $parameters) {
            return new Proxy($parameters['target'], $parameters['events']);
        });
    }

    public function provides()
    {
        return [ProxyManager::class, Proxy::class];
    }
}
