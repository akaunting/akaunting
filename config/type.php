<?php

use App\Models\Document\Document;

return [
    // Documents
    Document::INVOICE_TYPE => [
        'group' => 'sales',
        'route_name' => 'invoices',
        'route_parameter' => 'invoice',
        'permission_name' => 'sales-invoices',
        'translation_key' => 'invoices',
        'contact_type' => 'customer',
    ],

    Document::BILL_TYPE => [
        'group' => 'purchases',
        'route_name' => 'bills',
        'route_parameter' => 'bill',
        'permission_name' => 'purchases-bills',
        'translation_key' => 'bills',
        'contact_type' => 'vendor',
    ],

    // Contacts
    'customer' => [
        'permission_name' => 'sales-customers',
    ],

    'vendor' => [
        'permission_name' => 'purchases-vendors',
    ],

    // Transactions
    'income' => [
        'permission_name' => 'sales-revenues',
        'contact_type' => 'customer',
    ],

    'expense' => [
        'permission_name' => 'purchases-payments',
        'contact_type' => 'vendor',
    ],
];
