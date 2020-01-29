<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as Provider;

class Broadcast extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
