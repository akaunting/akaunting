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

        $this->reportUnsafeUploadProgress();
    }

    /**
     * Upload progress is a confirmed attack path of CVE-2026-109619 and has to be switched off in
     * php.ini or the FPM pool, which apply before the request is parsed. By the time this code runs
     * PHP has already parsed the request, so the app can only make a missing setting visible, once a day.
     *
     * @return void
     */
    protected function reportUnsafeUploadProgress()
    {
        if ($this->app->runningInConsole() || ! filter_var(ini_get('session.upload_progress.enabled'), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $key = 'security.upload_progress_reported';

        if (cache()->has($key)) {
            return;
        }

        cache()->put($key, true, now()->addDay());

        report('session.upload_progress.enabled is on for web requests: set it to 0 in php.ini or the FPM pool and restart PHP (CVE-2026-109619).');
    }
}
