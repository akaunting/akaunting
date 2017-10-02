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
            'App\Listeners\Updates\Version104',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
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
