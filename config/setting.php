<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Enable / Disable auto save
    |--------------------------------------------------------------------------
    |
    | Auto-save every time the application shuts down
    |
    */
	'auto_save'			=> env('SETTING_AUTO_SAVE', false),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Options for caching. Set whether to enable cache, its key, time to live
    | in seconds and whether to auto clear after save.
    |
    */
    'cache' => [
        'enabled'       => env('SETTING_CACHE_ENABLED', true),
        'key'           => env('SETTING_CACHE_KEY', 'setting'),
        'ttl'           => env('SETTING_CACHE_TTL', 21600),
        'auto_clear'    => env('SETTING_CACHE_AUTO_CLEAR', true),
    ],

	/*
    |--------------------------------------------------------------------------
    | Setting driver
    |--------------------------------------------------------------------------
    |
    | Select where to store the settings.
    |
    | Supported: "database", "json"
    |
    */
	'driver'			=> env('SETTING_DRIVER', 'database'),

	/*
    |--------------------------------------------------------------------------
    | Database driver
    |--------------------------------------------------------------------------
    |
    | Options for database driver. Enter which connection to use, null means
    | the default connection. Set the table and column names.
    |
    */
	'database' => [
		'connection'    => env('SETTING_DATABASE_CONNECTION', null),
		'table'         => env('SETTING_DATABASE_TABLE', 'settings'),
		'key'           => env('SETTING_DATABASE_KEY', 'key'),
		'value'         => env('SETTING_DATABASE_VALUE', 'value'),
	],

	/*
    |--------------------------------------------------------------------------
    | JSON driver
    |--------------------------------------------------------------------------
    |
    | Options for json driver. Enter the full path to the .json file.
    |
    */
	'json' => [
		'path'          => env('SETTING_JSON_PATH', storage_path('settings.json')),
	],

	/*
    |--------------------------------------------------------------------------
    | Override application config values
    |--------------------------------------------------------------------------
    |
    | If defined, settings package will override these config values.
    |
    | Sample:
    |   "app.fallback_locale",
    |   "app.locale" => "settings.locale",
    |
    */
	'override' => [

	],

    /*
    |--------------------------------------------------------------------------
    | Required Extra Columns
    |--------------------------------------------------------------------------
    |
    | The list of columns required to be set up
    |
    | Sample:
    |   "user_id",
    |   "tenant_id",
    |
    */
    'required_extra_columns' => [
        'company_id',
    ],
];
