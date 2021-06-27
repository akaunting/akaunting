<?php

use App\Models\Document\Document;

return [

    // Documents
    Document::INVOICE_TYPE => [
        'alias'                 => '', // core empty but module write own alias
        'group'                 => 'sales', // controller folder name for permission and route
        'route' => [
            'prefix'            => 'invoices', // core use with group + prefix, module ex. estimates
            'parameter'         => 'invoice', // sales/invoices/{parameter}/edit
            //'create'          => 'invoices.create', // if you change route, you can write full path
        ],
        'permission' => [
            'prefix'            => 'invoices', // this controller file name.
            //'create'          => 'create-sales-invoices', // if you change action permission key, you can write full permission
        ],
        'translation' => [
            'prefix'            => 'invoices', // this translation file name.
            'add_contact'       => 'general.customers', //
            'issued_at'         => 'invoices.invoice_date',
            'due_at'            => 'invoices.due_date',
        ],
        'setting' => [
            'prefix'            => 'invoice',
        ],
        'category_type'         => 'income',
        'transaction_type'      => 'income',
        'contact_type'          => 'customer', // use contact type
        'hide'                  => [], // for document items
        'class'                 => [],
        'notification' => [
            'class'             => 'App\Notifications\Sale\Invoice',
            'notify_contact'    => true,
            'notify_user'       => true,
        ],
    ],

    Document::BILL_TYPE => [
        'alias'                 => '',
        'group'                 => 'purchases',
        'route' => [
            'prefix'            => 'bills',
            'parameter'         => 'bill',
            //'create'          => 'bilss.create',
        ],
        'permission' => [
            'prefix'            => 'bills',
            //'create'          => 'create-purchases-bills',
        ],
        'translation' => [
            'prefix'            => 'bills',
            'issued_at'         => 'bills.bill_date',
            'due_at'            => 'bills.due_date',
        ],
        'setting' => [
            'prefix'            => 'bill',
        ],
        'category_type'         => 'expense',
        'transaction_type'      => 'expense',
        'contact_type'          => 'vendor',
        'hide'                  => [],
        'notification' => [
            'class'             => 'App\Notifications\Purchase\Bill',
            'notify_contact'    => false,
            'notify_user'       => true,
        ],
    ],

    // Contacts
    'customer' => [
        'group'                 => 'sales',
        'permission' => [
            'prefix'            => 'customers',
            //'create'          => 'create-sales-customers',
        ],
    ],

    'vendor' => [
        'group'                 => 'purchases',
        'permission' => [
            'prefix'            => 'vendors',
            //'create'          => 'create-purchases-vendors',
        ],
    ],

    // Transactions
    'income' => [
        'group'                 => 'sales',
        'route' => [
            'prefix'            => 'revenues', // core use with group + prefix, module ex. estimates
            'parameter'         => 'revenue', // sales/invoices/{parameter}/edit
            //'create'          => 'invoices.create', // if you change route, you can write full path
        ],
        'permission' => [
            'prefix'            => 'revenues',
            //'create'          => 'create-sales-revenues',
        ],
        'translation' => [
            'prefix'                    => 'revenues', // this translation file name.
            'related_document_amount'   => 'invoices.invoice_amount',
        ],
        'contact_type'          => 'customer',
    ],

    'expense' => [
        'group'                 => 'purchases',
        'route' => [
            'prefix'            => 'payments', // core use with group + prefix, module ex. estimates
            'parameter'         => 'payment', // sales/invoices/{parameter}/edit
            //'create'          => 'invoices.create', // if you change route, you can write full path
        ],
        'permission' => [
            'prefix'            => 'payments',
            //'create'          => 'create-purchases-payments',
        ],
        'translation' => [
            'prefix'                    => 'payments', // this translation file name.
            'related_document_amount'   => 'bills.bill_amount',
        ],
        'contact_type'          => 'vendor',
    ],

    // Categories
    'category' => [
        'income' => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        'expense' => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        'item' => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        'other' => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
    ],

];
