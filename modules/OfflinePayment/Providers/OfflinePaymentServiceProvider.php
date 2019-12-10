<?php

namespace Modules\OfflinePayment\Providers;

use App\Events\AdminMenuCreated;
use App\Events\PaymentGatewayListing;
use Illuminate\Support\ServiceProvider;
use Modules\OfflinePayment\Listeners\AdminMenu;
use Modules\OfflinePayment\Listeners\Gateway;

class OfflinePaymentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerMigrations();
        $this->registerEvents();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/offlinepayment');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/offlinepayment';
        }, \Config::get('view.paths')), [$sourcePath]), 'offlinepayment');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/offlinepayment');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'offlinepayment');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'offlinepayment');
        }
    }

    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function registerEvents()
    {
        $this->app['events']->listen(AdminMenuCreated::class, AdminMenu::class);
        $this->app['events']->listen(PaymentGatewayListing::class, Gateway::class);
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
