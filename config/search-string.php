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

    'fail' => 'all-results',

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
            'id',
            'name' => ['searchable' => true],
            'display_name' => ['searchable' => true],
            'description' => ['searchable' => true],
        ],
    ],

    App\Models\Auth\Role::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'display_name' => ['searchable' => true],
            'description' => ['searchable' => true],
        ],
    ],

    App\Models\Auth\User::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'email' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'last_logged_in_at' => ['date' => true],
        ],
    ],

    App\Models\Banking\Account::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'number' => ['searchable' => true],
            'bank_name' => ['searchable' => true],
            'bank_address' => ['searchable' => true],
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Banking\Reconciliation::class => [
        'columns' => [
            'id',
            'account' => ['relationship' => true],
            'closing_balance',
            'reconciled' => ['boolean' => true],
            'started_at' => ['date' => true],
            'ended_at' => ['date' => true],
        ],
    ],

    App\Models\Banking\Transaction::class => [
        'columns' => [
            'id',
            'type',
            'account_id' => [
                'route' => 'accounts.index'
            ],
            'paid_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'document_id',
            'contact_id',
            'description' => ['searchable' => true],
            'payment_method',
            'reference',
            'category_id' => [
                'route' => 'categories.index'
            ],
            'parent_id',
        ],
    ],

    App\Models\Banking\Transfer::class => [
        'columns' => [
            'id',
            'expense_account' => [
                'relationship' => true,
                'route' => 'accounts.index',
            ],
            'income_account' => [
                'relationship' => true,
                'route' => 'accounts.index',
            ],
        ],
    ],

    App\Models\Common\Company::class => [
        'columns' => [
            'id',
            'domain' => ['searchable' => true],
            'settings.value' => ['searchable' => true],
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Common\Dashboard::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Common\Item::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'description' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'category_id' => [
                'route' => ['categories.index', 'search=type:item']
            ],
            'sales_price',
            'purchase_price',
        ],
    ],

    App\Models\Common\Contact::class => [
        'columns' => [
            'id',
            'type',
            'name' => ['searchable' => true],
            'email' => ['searchable' => true],
            'tax_number' => ['searchable' => true],
            'phone' => ['searchable' => true],
            'address' => ['searchable' => true],
            'website' => ['searchable' => true],
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'reference',
            'user_id',
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Document\Document::class => [
        'columns' => [
            'id',
            'type' => ['searchable' => true],
            'document_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status',
            'issued_at' => [
                'key' => '/^(invoiced_at|billed_at)$/',
                'date' => true,
            ],
            'due_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'contact_id',
            'contact_name' => ['searchable' => true],
            'contact_email' => ['searchable' => true],
            'contact_tax_number',
            'contact_phone' => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id' => [
                'route' => 'categories.index'
            ],
            'parent_id',
        ],
    ],

    'App\Models\Purchase\Bill' => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status' => [
                'values' => [
                    'paid' => 'documents.statuses.paid',
                    'partial' => 'documents.statuses.partial',
                    'received' => 'documents.statuses.received',
                    'cancelled' => 'documents.statuses.cancelled',
                    'draft' => 'documents.statuses.draft',
                ],
            ],
            'issued_at' => [
                'key' => 'billed_at',
                'date' => true,
            ],
            'due_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'contact_id' => [
                'route' => 'vendors.index'
            ],
            'contact_name' => ['searchable' => true],
            'contact_email' => ['searchable' => true],
            'contact_tax_number',
            'contact_phone' => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id' => [
                'route' => ['categories.index', 'search=type:expense']
            ],
            'parent_id',
        ],
    ],

    'App\Models\Purchase\Payment' => [
        'columns' => [
            'id',
            'type',
            'account_id' => [
                'route' => 'accounts.index'
            ],
            'paid_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'document_id',
            'contact_id' => [
                'route' => 'vendors.index'
            ],
            'description' => ['searchable' => true],
            'payment_method',
            'reference',
            'category_id' => [
                'route' => ['categories.index', 'search=type:expense']
            ],
            'parent_id',
        ],
    ],

    'App\Models\Sale\Invoice' => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status' => [
                'values' => [
                    'paid' => 'documents.statuses.paid',
                    'partial' => 'documents.statuses.partial',
                    'sent' => 'documents.statuses.sent',
                    'viewed' => 'documents.statuses.viewed',
                    'cancelled' => 'documents.statuses.cancelled',
                    'draft' => 'documents.statuses.draft',
                ]
            ],
            'issued_at' => [
                'key' => 'invoiced_at',
                'date' => true,
            ],
            'due_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'contact_id' => [
                'route' => 'customers.index'
            ],
            'contact_name' => ['searchable' => true],
            'contact_email' => ['searchable' => true],
            'contact_tax_number',
            'contact_phone' => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id' => [
                'route' => ['categories.index', 'search=type:income']
            ],
            'parent_id',
        ],
    ],

    'App\Models\Sale\Revenue' => [
        'columns' => [
            'id',
            'type',
            'account_id' => [
                'route' => 'accounts.index'
            ],
            'paid_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'currencies.index'
            ],
            'document_id',
            'contact_id' => [
                'route' => 'customers.index'
            ],
            'description' => ['searchable' => true],
            'payment_method',
            'reference',
            'category_id' => [
                'route' => ['categories.index', 'search=type:income']
            ],
            'parent_id',
        ],
    ],

    App\Models\Setting\Category::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'type',
        ],
    ],

    App\Models\Setting\Currency::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'code' => ['searchable' => true],
            'rate' => ['searchable' => true],
            'enabled' => ['boolean' => true],
            'precision',
            'symbol',
            'symbol_first' => [
                'boolean' => true,
                'translation' => [
                    0 => 'currencies.symbol.after',
                    1 => 'currencies.symbol.before',
                ]
            ],
            'decimal_mark',
            'thousands_separator',
        ],
    ],

    App\Models\Setting\Tax::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'type',
            'rate',
            'enabled' => ['boolean' => true],
        ],
    ],

    App\Models\Portal\Sale\Invoice::class => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'order_number' => ['searchable' => true],
            'status',
            'issued_at' => [
                'key' => 'invoiced_at',
                'date' => true,
            ],
            'due_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'portal.payment.currencies'
            ],
            'parent_id',
        ],
    ],

    App\Models\Portal\Banking\Transaction::class => [
        'columns' => [
            'id',
            'paid_at' => ['date' => true],
            'amount',
            'currency_code' => [
                'route' => 'portal.payment.currencies'
            ],
            'document_id',
            'description' => ['searchable' => true],
            'payment_method',
            'reference',
            'parent_id',
        ],
    ],

];
