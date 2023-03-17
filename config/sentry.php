<?php

return [

    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD'))

    // When left empty or `null` the Laravel environment will be used
    'environment' => env('SENTRY_ENVIRONMENT'),

    'breadcrumbs' => [
        // Capture Laravel logs in breadcrumbs
        'logs' => env('SENTRY_BREADCRUMBS_LOGS', true),

        // Capture Laravel cache events in breadcrumbs
        'cache' => env('SENTRY_BREADCRUMBS_CACHE', true),

        // Capture Livewire components in breadcrumbs
        'livewire' => env('SENTRY_BREADCRUMBS_LIVEWIRE', true),

        // Capture SQL queries in breadcrumbs
        'sql_queries' => env('SENTRY_BREADCRUMBS_SQL_QUERIES', true),

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => env('SENTRY_BREADCRUMBS_SQL_BINDINGS', true),

        // Capture queue job information in breadcrumbs
        'queue_info' => env('SENTRY_BREADCRUMBS_QUEUE_INFO', true),

        // Capture command information in breadcrumbs
        'command_info' => env('SENTRY_BREADCRUMBS_COMMAND_INFO', true),

        // Capture HTTP client requests information in breadcrumbs
        'http_client_requests' => env('SENTRY_BREADCRUMBS_HTTP_CLIENT_REQUESTS', true),
    ],

    'tracing' => [
        // Trace queue jobs as their own transactions
        'queue_job_transactions' => env('SENTRY_TRACE_QUEUE_ENABLED', false),

        // Capture queue jobs as spans when executed on the sync driver
        'queue_jobs' => env('SENTRY_TRACE_QUEUE_JOBS', true),

        // Capture SQL queries as spans
        'sql_queries' => env('SENTRY_TRACE_SQL_QUERIES', true),

        // Try to find out where the SQL query originated from and add it to the query spans
        'sql_origin' => env('SENTRY_TRACE_SQL_ORIGIN', true),

        // Capture views as spans
        'views' => env('SENTRY_TRACE_VIEWS', true),

        // Capture Livewire components as spans
        'livewire' => env('SENTRY_TRACE_LIVEWIRE', true),

        // Capture HTTP client requests as spans
        'http_client_requests' => env('SENTRY_TRACE_HTTP_CLIENT_REQUESTS', true),

        // Capture Redis operations as spans (this enables Redis events in Laravel)
        'redis_commands' => env('SENTRY_TRACE_REDIS_COMMANDS', false),

        // Try to find out where the Redis command originated from and add it to the command spans
        'redis_origin' => env('SENTRY_TRACE_REDIS_ORIGIN', true),

        // Indicates if the tracing integrations supplied by Sentry should be loaded
        'default_integrations' => env('SENTRY_TRACE_DEFAULT_INTEGRATIONS', true),

        // Indicates that requests without a matching route should be traced
        'missing_routes' => env('SENTRY_TRACE_MISSING_ROUTES', false),
    ],

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#send-default-pii
    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', true),

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#traces-sample-rate
    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE') === null ? null : (float) env('SENTRY_TRACES_SAMPLE_RATE'),

    'before_send' => [env('SENTRY_BEFORE_SEND_CLASS', 'App\\Exceptions\\Trackers\\Sentry'), 'beforeSend'],

    'traces_sampler' => [env('SENTRY_TRACES_SAMPLER_CLASS', 'App\\Exceptions\\Trackers\\Sentry'), 'tracesSampler'],

];
