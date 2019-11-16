<?php

namespace App\Providers;

use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use Illuminate\Support\ServiceProvider as Provider;

class Observer extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        User::observe('App\Observers\User');
        Company::observe('App\Observers\Company');
        Transaction::observe('App\Observers\Transaction');
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
