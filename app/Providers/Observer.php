<?php

namespace App\Providers;

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use Illuminate\Support\ServiceProvider as Provider;

class Observer extends Provider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Contact::observe('App\Observers\GritchiContact');
        Document::observe('App\Observers\GritchiFinance');
        Transaction::observe('App\Observers\GritchiFinance');
        Transaction::observe('App\Observers\Transaction');
    }
}
