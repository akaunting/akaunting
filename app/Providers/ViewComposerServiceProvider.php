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

        // Suggestions
        View::composer(
            ['partials.admin.content'], 'App\Http\ViewComposers\Suggestions'
        );

        // Notifications
        View::composer(
            ['partials.admin.content'], 'App\Http\ViewComposers\Notifications'
        );

        // Add company info to menu
        View::composer(
            ['partials.admin.menu', 'partials.customer.menu'], 'App\Http\ViewComposers\Menu'
        );

        // Add notifications to header
        View::composer(
            ['partials.wizard.header', 'partials.admin.header', 'partials.customer.header'], 'App\Http\ViewComposers\Header'
        );

        // Add limits to index
        View::composer(
            '*.index', 'App\Http\ViewComposers\Index'
        );

        // Add Modules
        View::composer(
            'modules.*', 'App\Http\ViewComposers\Modules'
        );

        // Add recurring
        View::composer(
            ['partials.form.recurring',], 'App\Http\ViewComposers\Recurring'
        );

        // Add logo
        View::composer(
            ['incomes.invoices.invoice', 'expenses.bills.bill'], 'App\Http\ViewComposers\Logo'
        );

        // Add Invoice Text
        View::composer(
            ['incomes.invoices.*', 'customers.invoices.*'], 'App\Http\ViewComposers\InvoiceText'
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
