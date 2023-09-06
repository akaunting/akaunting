<?php

namespace Spatie\LaravelIgnition\Support;

class RunnableSolutionsGuard
{
    /**
     * Check if runnable solutions are allowed based on the current
     * environment and config.
     *
     * @return bool
     */
    public static function check(): bool
    {
        if (! config('app.debug')) {
            // Never run solutions in when debug mode is not enabled.

            return false;
        }

        if (config('ignition.enable_runnable_solutions') !== null) {
            // Allow enabling or disabling runnable solutions regardless of environment
            // if the IGNITION_ENABLE_RUNNABLE_SOLUTIONS env var is explicitly set.

            return config('ignition.enable_runnable_solutions');
        }

        if (! app()->environment('local') && ! app()->environment('development')) {
            // Never run solutions on non-local environments. This avoids exposing
            // applications that are somehow APP_ENV=production with APP_DEBUG=true.

            return false;
        }

        return config('app.debug');
    }
}
