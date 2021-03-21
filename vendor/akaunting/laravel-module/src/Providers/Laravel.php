<?php

namespace Akaunting\Module\Providers;

use Akaunting\Module\Contracts\RepositoryInterface;
use Akaunting\Module\Laravel\LaravelFileRepository;
use Akaunting\Module\Support\Stub;

class Laravel extends Main
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerModules();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $path = $this->app['config']->get('module.stubs.path') ?? __DIR__ . '/Commands/stubs';

        Stub::setBasePath($path);

        $this->app->booted(function ($app) {
            $repository = $app[RepositoryInterface::class];
            
            if ($repository->config('stubs.enabled') === true) {
                Stub::setBasePath($repository->config('stubs.path'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('module.paths.modules');

            return new LaravelFileRepository($app, $path);
        });
        
        $this->app->alias(RepositoryInterface::class, 'module');
    }
}
