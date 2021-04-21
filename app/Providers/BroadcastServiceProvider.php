<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as Provider;

class BroadcastServiceProvider extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        BroadcastServiceProvider::routes();

        require base_path('routes/channels.php');
    }
}
