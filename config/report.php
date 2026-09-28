<?php

return [

    'cache' => [

        /*
        |--------------------------------------------------------------------------
        | Serve the report pages (show, print, PDF, export) from the cache
        |--------------------------------------------------------------------------
        |
        | Data entered after a copy was cached shows once the refresh button is
        | used, the settings or the report change, or the day ends. Web servers
        | behind a load balancer must share one cache store.
        |
        */
        'enabled' => (bool) env('REPORT_CACHE_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Time to live for cached reports
        |--------------------------------------------------------------------------
        | The TTL is specified in seconds. After this period, the cached report
        | will be considered stale and will be regenerated.
        |
        */
        'ttl' => env('REPORT_CACHE_TTL', 86400), // One day

        /*
        |--------------------------------------------------------------------------
        | Settings whose changes do not invalidate the cached reports
        |--------------------------------------------------------------------------
        |
        | Dot-notation keys, wildcards allowed: per-user interface state and the
        | numbering counters (keys ending in _next) that every new record moves.
        | A module adds its own with config()->push() in its provider's boot().
        |
        */
        'ignored_settings' => [
            'favorites.*',
            'notifications.*',
            '*_next',
        ],

    ],

];
