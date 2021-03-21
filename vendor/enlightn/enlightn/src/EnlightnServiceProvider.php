<?php

namespace Enlightn\Enlightn;

use Enlightn\Enlightn\Inspection\Inspector;
use Enlightn\Enlightn\Reporting\API;
use Enlightn\Enlightn\Reporting\Client;
use Illuminate\Support\ServiceProvider;

class EnlightnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/enlightn.php' => config_path('enlightn.php'),
            ], 'enlightn');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\EnlightnCommand::class,
            Console\BaselineCommand::class,
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/enlightn.php', 'enlightn');

        $this->app->singleton(Inspector::class);
        $this->app->resolving(Inspector::class, function ($inspector) {
            $inspector->start(Enlightn::$filePaths->toArray());
        });

        $this->app->singleton(Composer::class, function ($app) {
            return new Composer($app->make('files'), $app->basePath());
        });

        $this->app->singleton(PHPStan::class, function ($app) {
            return new PHPStan($app->make('files'), $app->basePath());
        });
        $this->app->afterResolving(PHPStan::class, function ($PHPStan) {
            $PHPStan->start(Enlightn::$filePaths->toArray());
        });

        $this->app->singleton(NPM::class, function ($app) {
            return new NPM($app->make('files'), $app->basePath());
        });

        $this->app->singleton(API::class, function ($app) {
            $client = new Client(
                $app->config->get('enlightn.credentials.username'),
                $app->config->get('enlightn.credentials.api_token')
            );

            return new API($client);
        });
    }
}
