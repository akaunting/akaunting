<?php

namespace Modules\OfflinePayments\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Modules\OfflinePayments\Listeners\ShowPaymentMethod;
use Modules\OfflinePayments\Listeners\ShowSetting;

class Main extends Provider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslations();
        $this->loadMigrations();
        $this->loadViews();
        $this->loadEvents();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutes();
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'offline-payments');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'offline-payments');
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load events.
     *
     * @return void
     */
    public function loadEvents()
    {
        $this->app['events']->listen(\App\Events\Module\PaymentMethodShowing::class, ShowPaymentMethod::class);
        $this->app['events']->listen(\App\Events\Module\SettingShowing::class, ShowSetting::class);
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
            'portal.php',
            'signed.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
