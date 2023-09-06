<?php declare(strict_types=1);

namespace Wnx\LaravelStats;

use Illuminate\Support\ServiceProvider;
use Wnx\LaravelStats\Console\StatsListCommand;

class StatsServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    private $config = __DIR__.'/../config/stats.php';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->config => base_path('config/stats.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->config, 'stats');

        $this->commands([
            StatsListCommand::class,
        ]);
    }
}
