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
    | Fallback
    |--------------------------------------------------------------------------
    |
    | Define fallback settings to be used in case the default is null
    |
    | Sample:
    |   "currency" => "USD",
    |
    */
    'fallback' => [
        'localisation' => [
            'financial_start'           => env('SETTING_FALLBACK_LOCALISATION_FINANCIAL_START', '01-01'),
            'financial_denote'          => env('SETTING_FALLBACK_LOCALISATION_FINANCIAL_DENOTE', 'ends'),
            'timezone'                  => env('SETTING_FALLBACK_LOCALISATION_TIMEZONE', 'Europe/London'),
            'date_format'               => env('SETTING_FALLBACK_LOCALISATION_DATE_FORMAT', 'd M Y'),
            'date_separator'            => env('SETTING_FALLBACK_LOCALISATION_DATE_SEPARATOR', 'space'),
            'percent_position'          => env('SETTING_FALLBACK_LOCALISATION_PERCENT_POSITION', 'after'),
            'discount_location'         => env('SETTING_FALLBACK_LOCALISATION_DISCOUNT_LOCATION', 'total'),
        ],
        'invoice' => [
            'number_prefix'             => env('SETTING_FALLBACK_INVOICE_NUMBER_PREFIX', 'INV-'),
            'number_digit'              => env('SETTING_FALLBACK_INVOICE_NUMBER_DIGIT', '5'),
            'number_next'               => env('SETTING_FALLBACK_INVOICE_NUMBER_NEXT', '1'),
            'item_name'                 => env('SETTING_FALLBACK_INVOICE_ITEM_NAME', 'settings.invoice.item'),
            'price_name'                => env('SETTING_FALLBACK_INVOICE_PRICE_NAME', 'settings.invoice.price'),
            'quantity_name'             => env('SETTING_FALLBACK_INVOICE_QUANTITY_NAME', 'settings.invoice.quantity'),
            'hide_item_name'            => env('SETTING_FALLBACK_INVOICE_HIDE_ITEM_NAME', false),
            'hide_item_description'     => env('SETTING_FALLBACK_INVOICE_HIDE_ITEM_DESCRIPTION', false),
            'hide_quantity'             => env('SETTING_FALLBACK_INVOICE_HIDE_QUANTITY', false),
            'hide_price'                => env('SETTING_FALLBACK_INVOICE_HIDE_PRICE', false),
            'hide_amount'               => env('SETTING_FALLBACK_INVOICE_HIDE_AMOUNT', false),
            'payment_terms'             => env('SETTING_FALLBACK_INVOICE_PAYMENT_TERMS', '0'),
            'template'                  => env('SETTING_FALLBACK_INVOICE_TEMPLATE', 'default'),
            'color'                     => env('SETTING_FALLBACK_INVOICE_COLOR', '#55588b'),
            'logo_size_width'           => env('SETTING_FALLBACK_INVOICE_LOGO_SIZE_WIDTH', 128),
            'logo_size_height'          => env('SETTING_FALLBACK_INVOICE_LOGO_SIZE_HEIGHT', 128),
            'item_search_char_limit'    => env('SETTING_FALLBACK_INVOICE_ITEM_SEARCH_CHAR_LIMIT', 3),
        ],
        'bill' => [
            'number_prefix'             => env('SETTING_FALLBACK_BILL_NUMBER_PREFIX', 'BILL-'),
            'number_digit'              => env('SETTING_FALLBACK_BILL_NUMBER_DIGIT', '5'),
            'number_next'               => env('SETTING_FALLBACK_BILL_NUMBER_NEXT', '1'),
            'item_name'                 => env('SETTING_FALLBACK_BILL_ITEM_NAME', 'settings.bill.item'),
            'price_name'                => env('SETTING_FALLBACK_BILL_PRICE_NAME', 'settings.bill.price'),
            'quantity_name'             => env('SETTING_FALLBACK_BILL_QUANTITY_NAME', 'settings.bill.quantity'),
            'payment_terms'             => env('SETTING_FALLBACK_BILL_PAYMENT_TERMS', '0'),
            'template'                  => env('SETTING_FALLBACK_BILL_TEMPLATE', 'default'),
            'color'                     => env('SETTING_FALLBACK_BILL_COLOR', '#55588b'),
            'item_search_char_limit'    => env('SETTING_FALLBACK_BILL_ITEM_SEARCH_CHAR_LIMIT', 3),
        ],
        'default' => [
            'currency'                  => env('SETTING_FALLBACK_DEFAULT_CURRENCY', 'USD'),
            'locale'                    => env('SETTING_FALLBACK_DEFAULT_LOCALE', 'en-GB'),
            'list_limit'                => env('SETTING_FALLBACK_DEFAULT_LIST_LIMIT', '25'),
            'payment_method'            => env('SETTING_FALLBACK_DEFAULT_PAYMENT_METHOD', 'offline-payments.cash.1'),
            'select_limit'              => env('SETTING_FALLBACK_DEFAULT_SELECT_LIMIT', '10'),
        ],
        'email' => [
            'protocol'                  => env('SETTING_FALLBACK_EMAIL_PROTOCOL', 'mail'),
            'sendmail_path'             => env('SETTING_FALLBACK_EMAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
        ],
        'schedule' => [
            'send_invoice_reminder'     => env('SETTING_FALLBACK_SCHEDULE_SEND_INVOICE_REMINDER', '0'),
            'invoice_days'              => env('SETTING_FALLBACK_SCHEDULE_INVOICE_DAYS', '1,3,5,10'),
            'send_bill_reminder'        => env('SETTING_FALLBACK_SCHEDULE_SEND_BILL_REMINDER', '0'),
            'bill_days'                 => env('SETTING_FALLBACK_SCHEDULE_BILL_DAYS', '10,5,3,1'),
            'time'                      => env('SETTING_FALLBACK_SCHEDULE_TIME', '09:00'),
        ],
        'contact' => [
            'type' => [
                'customer'              => env('SETTING_FALLBACK_CONTACT_TYPE_CUSTOMER', 'customer'),
                'vendor'                => env('SETTING_FALLBACK_CONTACT_TYPE_VENDOR', 'vendor'),
            ],
        ],
        'transaction' => [
            'type' => [
                'income'                => env('SETTING_FALLBACK_TRANSACTION_TYPE_INCOME', 'income'),
                'expense'               => env('SETTING_FALLBACK_TRANSACTION_TYPE_EXPENSE', 'expense'),
            ],
        ],
        'transfer' => [
            'template'                  => env('SETTING_FALLBACK_BANKING_TEMPLATE', 'default'),
        ],
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
