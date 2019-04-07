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
            'App\Listeners\Updates\V10\Version106',
            'App\Listeners\Updates\V10\Version107',
            'App\Listeners\Updates\V10\Version108',
            'App\Listeners\Updates\V10\Version109',
            'App\Listeners\Updates\V11\Version110',
            'App\Listeners\Updates\V11\Version112',
            'App\Listeners\Updates\V11\Version113',
            'App\Listeners\Updates\V11\Version119',
            'App\Listeners\Updates\V12\Version120',
            'App\Listeners\Updates\V12\Version126',
            'App\Listeners\Updates\V12\Version127',
            'App\Listeners\Updates\V12\Version129',
            'App\Listeners\Updates\V12\Version1210',
            'App\Listeners\Updates\V12\Version1211',
            'App\Listeners\Updates\V13\Version130',
            'App\Listeners\Updates\V13\Version132',
            'App\Listeners\Updates\V13\Version135',
            'App\Listeners\Updates\V13\Version138',
            'App\Listeners\Updates\V13\Version139',
            'App\Listeners\Updates\V13\Version1311',
            'App\Listeners\Updates\V13\Version1313',
            'App\Listeners\Updates\V13\Version1316',
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
