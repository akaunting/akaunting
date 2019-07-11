<?php

namespace Modules\PaypalStandard\Providers;

use App\Events\PaymentGatewayListing;
use Illuminate\Support\ServiceProvider;
use Modules\PaypalStandard\Listeners\Gateway;

class PaypalStandardServiceProvider extends ServiceProvider
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
        $viewPath = resource_path('views/modules/paypalstandard');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/paypalstandard';
        }, \Config::get('view.paths')), [$sourcePath]), 'paypalstandard');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/paypalstandard');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'paypalstandard');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'paypalstandard');
        }
    }

    public function registerEvents()
    {
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
