<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // All
        View::composer(
            '*', 'App\Http\ViewComposers\All'
        );

        // Add company info to menu
        View::composer(
            ['partials.admin.menu', 'partials.customer.menu'], 'App\Http\ViewComposers\Menu'
        );

        // Add notifications to header
        View::composer(
            ['partials.admin.header', 'partials.customer.header'], 'App\Http\ViewComposers\Header'
        );

        // Add limits to index
        View::composer(
            '*.index', 'App\Http\ViewComposers\Index'
        );

        // Add Modules
        View::composer(
            'modules.*', 'App\Http\ViewComposers\Modules'
        );
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
}
