<?php

namespace Sentry\Laravel\Features;

use Illuminate\Support\Facades\Log;
use Sentry\Laravel\LogChannel;

class LogIntegration extends Feature
{
    public function isApplicable(): bool
    {
        return true;
    }

    public function register(): void
    {
        Log::extend('sentry', function ($app, array $config) {
            return (new LogChannel($app))($config);
        });
    }
}
