<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UpdateFinished' => [
            'App\Listeners\Updates\Version106',
            'App\Listeners\Updates\Version107',
            'App\Listeners\Updates\Version108',
            'App\Listeners\Updates\Version109',
            'App\Listeners\Updates\Version110',
            'App\Listeners\Updates\Version112',
            'App\Listeners\Updates\Version113',
            'App\Listeners\Updates\Version119',
            'App\Listeners\Updates\Version120',
            'App\Listeners\Updates\Version126',
            'App\Listeners\Updates\Version127',
            'App\Listeners\Updates\Version129',
            'App\Listeners\Updates\Version1210',
            'App\Listeners\Updates\Version1211',
            'App\Listeners\Updates\Version130',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        'App\Events\InvoicePaid' => [
            'App\Listeners\Incomes\Invoice\Paid',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
