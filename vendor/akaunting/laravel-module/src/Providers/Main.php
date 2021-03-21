<?php

namespace Akaunting\Module\Providers;

use Akaunting\Module\Contracts\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

abstract class Main extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
    }

    /**
     * Register all modules.
     */
    public function register()
    {
    }

    /**
     * Register all modules.
     */
    protected function registerModules()
    {
        $this->app->register(Bootstrap::class);
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/module.php', 'module');
        
        $this->publishes([
            __DIR__ . '/../Config/module.php' => config_path('module.php'),
        ], 'module');
    }

    /**
     * Register the service provider.
     */
    abstract protected function registerServices();

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [RepositoryInterface::class, 'module'];
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(Console::class);
        $this->app->register(Contracts::class);
    }
}
