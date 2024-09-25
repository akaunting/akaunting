<?php

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;

return [

    // Categories
    'category' => [
        Category::INCOME_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],

        Category::EXPENSE_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],

        Category::ITEM_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],

        Category::OTHER_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
    ],

    // Contacts
    'contact' => [
        Contact::CUSTOMER_TYPE => [
            'alias'                 => '', // core empty but module write own alias
            'group'                 => 'sales',
            'route' => [
                'prefix'            => 'customers', // core use with group + prefix, module ex. estimates
                'parameter'         => 'customer', // sales/customers/{parameter}/edit
                //'create'          => 'customers.create', // if you change route, you can write full path
            ],
            'permission' => [
                'prefix'            => 'customers',
                //'create'          => 'create-sales-customers',
            ],
            'translation' => [
                'prefix'                        => 'customers', // this translation file name.
                'section_general_description'   => 'customers.form_description.general',
                'section_billing_description'   => 'customers.form_description.billing',
                'section_address_description'   => 'customers.form_description.address',
            ],
            'category_type'         => 'income',
            'document_type'         => 'invoice',
            'transaction_type'      => 'income',
            'hide'                  => [],
            'class'                 => [],
            'script' => [
                'folder'            => 'common',
                'file'              => 'contacts',
            ],
        ],

        Contact::VENDOR_TYPE => [
            'alias'                 => '', // core empty but module write own alias
            'group'                 => 'purchases',
            'route' => [
                'prefix'            => 'vendors', // core use with group + prefix, module ex. estimates
                'parameter'         => 'vendor', // sales/vendors/{parameter}/edit
                //'create'          => 'vendors.create', // if you change route, you can write full path
            ],
            'permission' => [
                'prefix'            => 'vendors',
                //'create'          => 'create-purchases-vendors',
            ],
            'translation' => [
                'prefix'                        => 'vendors', // this translation file name.
                'section_general_description'   => 'vendors.form_description.general',
                'section_billing_description'   => 'vendors.form_description.billing',
                'section_address_description'   => 'vendors.form_description.address',
            ],
            'category_type'         => 'expense',
            'document_type'         => 'bill',
            'transaction_type'      => 'expense',
            'hide'                  => [],
            'class'                 => [],
            'script' => [
                'folder'            => 'common',
                'file'              => 'contacts',
            ],
        ],
    ],

    // Documents
    'document' => [
        Document::INVOICE_TYPE => [
            'alias'                     => '', // core empty but module write own alias
            'group'                     => 'sales', // controller folder name for permission and route
            'route' => [
                'prefix'                => 'invoices', // core use with group + prefix, module ex. estimates
                'parameter'             => 'invoice', // sales/invoices/{parameter}/edit
                'document'              => 'invoices.index',
                'recurring'             => 'recurring-invoices.index',
                //'create'              => 'invoices.create', // if you change route, you can write full path
                'params' => [
                    'unpaid'            => ['search' => 'status:sent,viewed,partial'],
                    'draft'             => ['search' => 'status:draft'],
                    'all'               => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'                => 'invoices', // this controller file name.
                //'create'              => 'create-sales-invoices', // if you change action permission key, you can write full permission
            ],
            'translation' => [
                'prefix'                        => 'invoices', // this translation file name.
                'add_contact'                   => 'general.customers', //
                'issued_at'                     => 'invoices.invoice_date',
                'due_at'                        => 'invoices.due_date',
                'section_billing_description'   => 'invoices.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'invoice',
            ],
            'category_type'             => 'income',
            'transaction_type'          => 'income',
            'contact_type'              => 'customer', // use contact type
            'inventory_stock_action'    => 'decrease', // decrease stock in stock tracking
            'transaction' => [
                'email_template'        => 'invoice_payment_customer', // use email template
            ],
            'hide'                      => [], // for document items
            'class'                     => [],
            'notification' => [
                'class'                 => 'App\Notifications\Sale\Invoice',
                'notify_contact'        => true,
                'notify_user'           => true,
            ],
            'auto_send' => 'App\Events\Document\DocumentSent',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'send',
                'sent'                  => 'get-paid',
                'viewed'                => 'get-paid',
                'partial'               => 'get-paid',
                'paid'                  => 'get-paid',
                'cancelled'             => 'restore',
            ],
        ],

        Document::INVOICE_RECURRING_TYPE => [
            'alias'                     => '', // core empty but module write own alias
            'group'                     => 'sales', // controller folder name for permission and route
            'route' => [
                'prefix'                => 'recurring-invoices', // core use with group + prefix, module ex. estimates
                'parameter'             => 'recurring_invoice', // sales/invoices/{parameter}/edit
                'document'              => 'invoices.index',
                'recurring'             => 'recurring-invoices.index',
                'end'                   => 'recurring-invoices.end',
                //'create'              => 'invoices.create', // if you change route, you can write full path
            ],
            'permission' => [
                'prefix'                => 'invoices', // this controller file name.
                //'create'              => 'create-sales-invoices', // if you change action permission key, you can write full permission
            ],
            'translation' => [
                'prefix'                        => 'invoices', // this translation file name.
                'add_contact'                   => 'general.customers', //
                'issued_at'                     => 'invoices.invoice_date',
                'due_at'                        => 'invoices.due_date',
                'tab_document'                  => 'general.invoices',
                'section_billing_description'   => 'invoices.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'invoice',
            ],
            'category_type'             => 'income',
            'transaction_type'          => 'income',
            'contact_type'              => 'customer', // use contact type
            'inventory_stock_action'    => 'decrease', // decrease stock in stock tracking
            'hide'                      => [], // for document items
            'class'                     => [],
            'notification' => [

            ],
            'auto_send'                 => 'App\Events\Document\DocumentSent',
            'image_empty_page'          => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'schedule',
                'active'                => 'schedule',
                'end'                   => 'schedule',
            ],
        ],

        Document::BILL_TYPE => [
            'alias'                     => '',
            'group'                     => 'purchases',
            'route' => [
                'prefix'                => 'bills',
                'parameter'             => 'bill',
                'document'              => 'bills.index',
                'recurring'             => 'recurring-bills.index',
                //'create'              => 'bilss.create',
                'params' => [
                    'unpaid'            => ['search' => 'status:received,partial'],
                    'draft'             => ['search' => 'status:draft'],
                    'all'               => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'                => 'bills',
                //'create'              => 'create-purchases-bills',
            ],
            'translation' => [
                'prefix'                        => 'bills',
                'issued_at'                     => 'bills.bill_date',
                'due_at'                        => 'bills.due_date',
                'section_billing_description'   => 'bills.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'bill',
            ],
            'category_type'             => 'expense',
            'transaction_type'          => 'expense',
            'contact_type'              => 'vendor',
            'inventory_stock_action'    => 'increase', // increases stock in stock tracking
            'transaction' => [
                'email_template'        => 'invoice_payment_customer', // use email template
            ],
            'hide'                      => [],
            'notification' => [
                'class'                 => 'App\Notifications\Purchase\Bill',
                'notify_contact'        => false,
                'notify_user'           => true,
            ],
            'auto_send' => 'App\Events\Document\DocumentReceived',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'receive',
                'received'              => 'make-payment',
                'viewed'                => 'make-payment',
                'partial'               => 'make-payment',
                'paid'                  => 'make-payment',
                'cancelled'             => 'restore',
            ],
        ],

        Document::BILL_RECURRING_TYPE => [
            'alias'                     => '',
            'group'                     => 'purchases',
            'route' => [
                'prefix'                => 'recurring-bills',
                'parameter'             => 'recurring_bill',
                'document'              => 'bills.index',
                'recurring'             => 'recurring-bills.index',
                'end'                   => 'recurring-bills.end',
                //'create'              => 'bilss.create',
            ],
            'permission' => [
                'prefix'                => 'bills',
                //'create'              => 'create-purchases-bills',
            ],
            'translation' => [
                'prefix'                        => 'bills',
                'issued_at'                     => 'bills.bill_date',
                'due_at'                        => 'bills.due_date',
                'tab_document'                  => 'general.bills',
                'section_billing_description'   => 'bills.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'bill',
            ],
            'category_type'             => 'expense',
            'transaction_type'          => 'expense',
            'contact_type'              => 'vendor',
            'inventory_stock_action'    => 'increase', // increases stock in stock tracking
            'hide'                      => [],
            'notification' => [

            ],
            'auto_send'                 => 'App\Events\Document\DocumentReceived',
            'image_empty_page'          => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'schedule',
                'active'                => 'schedule',
                'end'                   => 'schedule',
            ],
        ],
    ],

    // Transactions
    'transaction' => [
        'transactions' => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
 
        Transaction::INCOME_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::INCOME_TRANSFER_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::INCOME_SPLIT_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::INCOME_RECURRING_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'recurring-transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'recurring_transaction', // banking/recurring-transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'            => 'transactions', // this translation file name.
                'new'               => 'general.recurring_incomes',
                'transactions'      => 'general.incomes',
            ],
            'image_empty_page'      => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
            'status_workflow' => [
                'draft'             => 'schedule',
                'active'            => 'schedule',
                'end'               => 'schedule',
            ],
        ],

        Transaction::EXPENSE_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'split_type'            => Transaction::EXPENSE_SPLIT_TYPE,
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::EXPENSE_TRANSFER_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'split_type'            => Transaction::EXPENSE_SPLIT_TYPE,
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::EXPENSE_SPLIT_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'transaction', // banking/transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', // this translation file name.
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],

        Transaction::EXPENSE_RECURRING_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'recurring-transactions', // core use with group + prefix, module ex. estimates
                'parameter'         => 'recurring_transaction', // banking/recurring-transactions/{parameter}/edit
                //'create'          => 'transactions.create', // if you change route, you can write full path
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
                //'create'          => 'create-banking-transactions',
            ],
            'translation' => [
                'prefix'            => 'transactions', // this translation file name.
                'new'               => 'general.recurring_expenses',
                'transactions'      => 'general.expenses',
            ],
            'image_empty_page'      => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
            'status_workflow' => [
                'draft'             => 'schedule',
                'active'            => 'schedule',
                'end'               => 'schedule',
            ],
        ],
    ],

];
