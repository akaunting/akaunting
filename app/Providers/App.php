<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as Provider;
use Laravel\Sanctum\Sanctum;

class App extends Provider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.installed') && config('app.debug')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        if (! env_is_production()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Laravel db fix
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();

        Model::preventLazyLoading(config('app.eager_load'));

        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            if (config('logging.default') == 'sentry') {
                \Sentry\Laravel\Integration::lazyLoadingViolationReporter();
            } else {
                $class = get_class($model);

                report("Attempted to lazy load [{$relation}] on model [{$class}].");
            }
        });
    }
}
