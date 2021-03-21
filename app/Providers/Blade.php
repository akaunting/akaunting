<?php

namespace App\Providers;

use App\Traits\DateTime;
use Illuminate\Support\Facades\Blade as Facade;
use Illuminate\Support\ServiceProvider;

class Blade extends ServiceProvider
{
    use DateTime;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Facade::directive('date', function ($expression) {
            return "<?php echo company_date($expression); ?>";
        });

        Facade::directive('widget', function ($expression) {
            return "<?php echo show_widget($expression); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
