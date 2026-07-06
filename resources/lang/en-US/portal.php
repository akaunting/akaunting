<?php

return [

    'profile'               => 'Profile',
    'invoices'              => 'Invoices',
    'payments'              => 'Payments',
    'payment_received'      => 'Payment received, thank you!',
    'create_your_invoice'   => 'Now create your own invoice — it\'s free',
    'get_started'           => 'Get started for free',
    'billing_address'       => 'Billing Address',
    'see_all_details'       => 'See all account details',
    'all_payments'          => 'Login to view all payments',
    'received_date'         => 'Received Date',
    'redirect_description'  => 'You will be redirected to the :name website to make the payment.',

    'last_payment'          => [
        'title'             => 'Last Payment Made',
        'description'       => 'You have made this payment on :date',
        'not_payment'       => 'You have not made any payment, yet.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Outstanding balance',
        'description'       => 'Your outstanding balance is:',
        'not_payment'       => 'You have not outstanding balance, yet.',
    ],

    'latest_invoices'       => [
        'title'             => 'Latest Invoices',
        'description'       => ':date - You were billed with invoice number :invoice_number.',
        'no_data'           => 'You have not invoice, yet.',
    ],

    'invoice_history'       => [
        'title'             => 'Invoice History',
        'description'       => ':date - You were billed with invoice number :invoice_number.',
        'no_data'           => 'You have not invoice history, yet.',
    ],

    'payment_history'       => [
        'title'             => 'Payment History',
        'description'       => ':date - You made a payment of :amount.',
        'invoice_description'=> ':date - You made a payment of :amount for invoice number :invoice_number.',

        'no_data'           => 'You have not payment history, yet.',
    ],

    'payment_detail'        => [
        'description'       => 'You made a :amount payment on :date for this invoice.'
    ],

];
