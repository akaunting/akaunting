<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Invalid search string handling
    |--------------------------------------------------------------------------
    |
    | - all-results: (Default) Silently fail with a query containing everything.
    | - no-results: Silently fail with a query containing nothing.
    | - exceptions: Throw an `InvalidSearchStringException`.
    |
    */

    'fail' => 'no-results',

    /*
    |--------------------------------------------------------------------------
    | Default options
    |--------------------------------------------------------------------------
    |
    | When options are missing from your models, this array will be used
    | to fill the gaps. You can also define a set of options specific
    | to a model, using its class name as a key, e.g. 'App\User'.
    |
    */

    'default' => [
        'keywords' => [
            'order_by' => 'sort',
            'select' => 'fields',
            'limit' => 'limit',
            'offset' => 'page',
        ],
        'columns' => [
            'created_at' => 'date',
        ],
    ],

    App\Models\Auth\Permission::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'display_name' => ['searchable' => true],
            'description' => ['searchable' => true],
        ],
    ],

    App\Models\Auth\Role::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'display_name' => ['searchable' => true],
            'description' => ['searchable' => true],
        ],
    ],

    App\Models\Auth\User::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'email' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'last_logged_in_at' => ['date' => true],
        ],
    ],

    App\Models\Banking\Account::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'number' => ['searchable' => true],
            'bank_name' => ['searchable' => true],
            'bank_address' => ['searchable' => true],
            'currency_code',
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Banking\Reconciliation::class => [
        'columns' => [
            'account_id',
            'closing_balance',
            'reconciled' => ['boolean' => true],
            'started_at' => ['date' => true],
            'ended_at' => ['date' => true],
        ],
    ],

    App\Models\Banking\Transaction::class => [
        'columns' => [
            'type',
            'account_id',
            'paid_at' => ['date' => true],
            'amount',
            'currency_code',
            'document_id',
            'contact_id',
            'description' => ['searchable' => true],
            'payment_method',
            'reference',
            'category_id',
            'parent_id',
        ],
    ],

    App\Models\Common\Company::class => [
        'columns' => [
            'domain' => ['searchable' => true],
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Common\Dashboard::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Common\Item::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'description' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'category_id' => ['key' => 'category_id'],
            'sale_price',
            'purchase_price',
        ],
    ],

    App\Models\Common\Contact::class => [
        'columns' => [
            'type',
            'name' => ['searchable' => true],
            'email' => ['searchable' => true],
            'tax_number' => ['searchable' => true],
            'phone' => ['searchable' => true],
            'address' => ['searchable' => true],
            'website' => ['searchable' => true],
            'currency_code',
            'reference',
            'user_id',
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Purchase\Bill::class => [
        'columns' => [
            'bill_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status',
            'billed_at' => ['date' => true],
            'due_at' => ['date' => true],
            'amount',
            'currency_code',
            'contact_id',
            'contact_name' => ['searchable' => true],
            'contact_email' => ['searchable' => true],
            'contact_tax_number',
            'contact_phone' => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id',
            'parent_id',
        ],
    ],

    App\Models\Sale\Invoice::class => [
        'columns' => [
            'invoice_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status',
            'invoiced_at' => ['date' => true],
            'due_at' => ['date' => true],
            'amount',
            'currency_code',
            'contact_id',
            'contact_name' => ['searchable' => true],
            'contact_email' => ['searchable' => true],
            'contact_tax_number',
            'contact_phone' => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id',
            'parent_id',
        ],
    ],

    App\Models\Setting\Category::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'type',
        ],
    ],

    App\Models\Setting\Currency::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'code' => ['searchable' => true],
            'rate' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'precision',
            'symbol',
            'symbol_first' => ['boolean' => true],
            'decimal_mark',
            'thousands_separator',
        ],
    ],

    App\Models\Setting\Tax::class => [
        'columns' => [
            'name' => ['searchable' => true],
            'type',
            'rate',
            'enabled' => ['boolean' => true],
        ],
    ],

];
