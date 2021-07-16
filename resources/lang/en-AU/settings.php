<?php

return [

    'company' => [
        'description'                => 'Change company name, email, address, tax number etc',
        'name'                       => 'Name',
        'email'                      => 'Email',
        'phone'                      => 'Phone',
        'address'                    => 'Address',
        'edit_your_business_address' => 'Edit your business address',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
        'financial_start'   => 'Financial Year Start',
        'timezone'          => 'Time Zone',
        'financial_denote' => [
            'title'         => 'Financial Year Denote',
            'begins'        => 'By the year in which it begins',
            'ends'          => 'By the year in which it ends',
        ],
        'date' => [
            'format'        => 'Date Format',
            'separator'     => 'Date Separator',
            'dash'          => 'Dash (-)',
            'dot'           => 'Dot (.)',
            'comma'         => 'Comma (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Space ( )',
        ],
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
        'discount_location' => [
            'name'          => 'Discount Location',
            'item'          => 'At line',
            'total'         => 'At total',
            'both'          => 'Both line and total',
        ],
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'Number Prefix',
        'digit'             => 'Number Digit',
        'next'              => 'Next Number',
        'logo'              => 'Logo',
        'custom'            => 'Custom',
        'item_name'         => 'Item Name',
        'item'              => 'Items',
        'product'           => 'Products',
        'service'           => 'Services',
        'price_name'        => 'Price Name',
        'price'             => 'Price',
        'rate'              => 'Rate',
        'quantity_name'     => 'Quantity Name',
        'quantity'          => 'Quantity',
        'payment_terms'     => 'Payment Terms',
        'title'             => 'Title',
        'subheading'        => 'Subheading',
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
        'choose_template'   => 'Choose invoice template',
        'default'           => 'Default',
        'classic'           => 'Classic',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Hide Item Name',
            'item_description'  => 'Hide Item Description',
            'quantity'          => 'Hide Quantity',
            'price'             => 'Hide Price',
            'amount'            => 'Hide Amount',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Choose transfer template',
        'second'            => 'Second',
        'third'             => 'Third',
    ],

    'default' => [
        'description'       => 'Default account, currency, language of your company',
        'list_limit'        => 'Records Per Page',
        'use_gravatar'      => 'Use Gravatar',
        'income_category'   => 'Income Category',
        'expense_category'  => 'Expense Category',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
        'protocol'          => 'Protocol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP Username',
            'password'      => 'SMTP Password',
            'encryption'    => 'SMTP Security',
            'none'          => 'None',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Log Emails',

        'templates' => [
            'subject'                   => 'Subject',
            'body'                      => 'Body',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_payment_customer'  => 'Payment Received Template (sent to customer)',
            'invoice_payment_admin'     => 'Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
            'revenue_new_customer'      => 'Revenue Received Template (sent to customer)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Scheduling',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'Send Invoice Reminder',
        'invoice_days'      => 'Send After Due Days',
        'send_bill'         => 'Send Bill Reminder',
        'bill_days'         => 'Send Before Due Days',
        'cron_command'      => 'Cron Command',
        'schedule_time'     => 'Hour To Run',
    ],

    'categories' => [
        'description'       => 'Unlimited categories for income, expense, and item',
    ],

    'currencies' => [
        'description'       => 'Create and manage currencies and set their rates',
    ],

    'taxes' => [
        'description'       => 'Fixed, normal, inclusive, and compound tax rates',
    ],

];
