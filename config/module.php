<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => true,
        'path' => base_path('app/Console/Stubs/Modules'),
        'files' => [
            'listeners/install' => 'Listeners/FinishInstallation.php',
            'providers/event' => 'Providers/Event.php',
            'routes/admin' => 'Routes/admin.php',
            'routes/portal' => 'Routes/portal.php',
            'lang/general' => 'Resources/lang/en-GB/general.php',
            'views/index' => 'Resources/views/index.blade.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'Resources/assets/js/app.js',
            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            'webpack' => 'webpack.mix.js',
            'package' => 'package.json',
        ],
        'replacements' => [
            'listeners/install' => ['ALIAS', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'providers/event' => ['ALIAS', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/admin' => ['ALIAS', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'routes/portal' => ['ALIAS', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'webpack' => ['ALIAS'],
            'json' => ['ALIAS', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'lang/general' => ['ALIAS', 'STUDLY_NAME'],
            'views/index' => ['ALIAS', 'STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
            ],
        ],
        'gitkeep' => false,
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */
        'modules' => base_path(env('MODULE_PATHS_MODULES', 'modules')),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */
        'assets' => public_path(env('MODULE_PATHS_ASSETS', 'modules')),

        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path(env('MODULE_PATHS_MIGRATION', 'database/migrations')),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key to false to not generate that folder
        */
        'generator' => [
            'config' => ['path' => 'Config', 'generate' => false],
            'command' => ['path' => 'Console', 'generate' => true],
            'migration' => ['path' => 'Database/Migrations', 'generate' => true],
            'seeder' => ['path' => 'Database/Seeds', 'generate' => true],
            'factory' => ['path' => 'Database/Factories', 'generate' => true],
            'model' => ['path' => 'Models', 'generate' => true],
            'controller' => ['path' => 'Http/Controllers', 'generate' => true],
            'middleware' => ['path' => 'Http/Middleware', 'generate' => false],
            'request' => ['path' => 'Http/Requests', 'generate' => true],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'resource' => ['path' => 'Http/Resources', 'generate' => false],
            'asset' => ['path' => 'Resources/assets', 'generate' => false],
            'lang' => ['path' => 'Resources/lang/en-GB', 'generate' => true],
            'view' => ['path' => 'Resources/views', 'generate' => true],
            'test' => ['path' => 'Tests', 'generate' => false],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            'event' => ['path' => 'Events', 'generate' => false],
            'listener' => ['path' => 'Listeners', 'generate' => true],
            'policy' => ['path' => 'Policies', 'generate' => false],
            'rule' => ['path' => 'Rules', 'generate' => false],
            'job' => ['path' => 'Jobs', 'generate' => true],
            'email' => ['path' => 'Emails', 'generate' => false],
            'notification' => ['path' => 'Notifications', 'generate' => false],
            'route' => ['path' => 'Routes', 'generate' => true],
            'component' => ['path' => 'View/Components', 'generate' => false],
            'cast' => ['path' => 'Casts', 'generate' => false],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => 'akaunting',
        'author' => [
            'name' => 'Akaunting',
            'email' => 'info@akaunting.com',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'module',
        'lifetime' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what module will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         *
         * @example boot|register
         */
        'files' => 'register',
    ],

];
