<?php

return [

    'enabled' => env('MODEL_CACHE_ENABLED', true),

    'cache-prefix' => env('MODEL_CACHE_PREFIX', 'model'),

    'use-database-keying' => env('MODEL_CACHE_USE_DATABASE_KEYING', true),

    'store' => env('MODEL_CACHE_STORE', 'array'),

];
