<?php

namespace Lorisleiva\LaravelSearchString;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Lorisleiva\LaravelSearchString\Console;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\DumpAstCommand::class,
                Console\DumpSqlCommand::class,
                Console\DumpResultCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/config.php' => config_path('search-string.php'),
        ], 'search-string');
    }
}
