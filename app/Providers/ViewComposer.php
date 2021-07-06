<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use View;

class ViewComposer extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Suggestions
        View::composer(
            'partials.admin.header', 'App\Http\ViewComposers\Suggestions'
        );

        // Notifications
        View::composer(
            'partials.admin.content', 'App\Http\ViewComposers\Notifications'
        );

        // Add company info to menu
        View::composer(
            ['partials.admin.menu', 'partials.portal.menu'],
            'App\Http\ViewComposers\Menu'
        );

        // Add notifications to header
        View::composer(
            ['partials.wizard.navbar', 'partials.admin.navbar', 'partials.portal.navbar'],
            'App\Http\ViewComposers\Header'
        );

        // Add limits and bulk actions to index
        View::composer(
            '*.index', 'App\Http\ViewComposers\Index'
        );

        // Add limits to show
        View::composer(
            '*.show', 'App\Http\ViewComposers\Index'
        );

        // Add Modules
        View::composer(
            'modules.*', 'App\Http\ViewComposers\Modules'
        );

        // Add recurring
        View::composer(
            'partials.form.recurring', 'App\Http\ViewComposers\Recurring'
        );

        // Add Document Type
        View::composer(
            ['documents.*', 'portal.documents.*'],
            'App\Http\ViewComposers\DocumentType'
        );

        // Wizard
        View::composer(
            'layouts.wizard', 'App\Http\ViewComposers\Wizard'
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
