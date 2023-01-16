<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flag to enable/disable read-only mode from the .env file
    |--------------------------------------------------------------------------
    */
    'enabled'       => env('READ_ONLY_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Enable read-only mode but still allow users to login
    |--------------------------------------------------------------------------
    */
    'allow_login'   => env('READ_ONLY_LOGIN', true),

    /*
    |--------------------------------------------------------------------------
    | The login/logout routes to allow if allow_login=true
    |--------------------------------------------------------------------------
    */
    'login_route'   => 'login.store',
    'logout_route'  => 'logout',

    /*
    |--------------------------------------------------------------------------
    | The request methods that you want to block
    |--------------------------------------------------------------------------
    */
    'methods'       => explode(',', env('READ_ONLY_METHODS', 'post,put,patch,delete')),

    /*
    |--------------------------------------------------------------------------
    | Whitelist certain request methods to certain routes
    |--------------------------------------------------------------------------
    */
    'whitelist' => [
        // 'post'   => 'dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Skip livewire paths
    |--------------------------------------------------------------------------
    */
    'livewire'      => explode(',', env('READ_ONLY_LIVEWIRE', 'menu.notifications,menu.settings,menu.neww')),

];
