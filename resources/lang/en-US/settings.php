<?php

return [

    'company' => [
        'description'                   => 'Change company name, email, address, tax number etc',
        'search_keywords'               => 'company, name, email, phone, address, country, tax number, logo, city, town, state, province, zip code',
        'name'                          => 'Name',
        'email'                         => 'Email',
        'phone'                         => 'Phone',
        'address'                       => 'Address',
        'edit_your_business_address'    => 'Edit your business address',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'This information is visible in the records you create.',
            'address'                   => 'The address will be used in the invoices, bills, and other records that you issue.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Set fiscal year, time zone, date format and more localizations',
        'search_keywords'               => 'financial, year, start, denote, time, zone, date, format, separator, discount, percent',
        'financial_start'               => 'Financial Year Start',
        'timezone'                      => 'Time Zone',
        'financial_denote' => [
            'title'                     => 'Financial Year Denote',
            'begins'                    => 'By the year in which it begins',
            'ends'                      => 'By the year in which it ends',
        ],
        'preferred_date'                => 'Preferred Date',
        'date' => [
            'format'                    => 'Date Format',
            'separator'                 => 'Date Separator',
            'dash'                      => 'Dash (-)',
            'dot'                       => 'Dot (.)',
            'comma'                     => 'Comma (,)',
            'slash'                     => 'Slash (/)',
            'space'                     => 'Space ( )',
        ],
        'percent' => [
            'title'                     => 'Percent (%) Position',
            'before'                    => 'Before Number',
            'after'                     => 'After Number',
        ],
        'discount_location' => [
            'name'                      => 'Discount Location',
            'item'                      => 'At line',
            'total'                     => 'At total',
            'both'                      => 'Both line and total',
        ],

        'form_description' => [
            'fiscal'                    => 'Set the financial year period that your company uses for taxing and reporting.',
            'date'                      => 'Select the date format that you want to see everywhere in the interface.',
            'other'                     => 'Select the where the percentage sign is displayed for taxes. You can enable discounts on line items and at the total for invoices and bills.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Customize invoice prefix, number, terms, footer etc',
        'search_keywords'               => 'customize, invoice, number, prefix, digit, next, logo, name, price, quantity, template, title, subheading, footer, note, hide, due, colour, payment, terms, column',
        'prefix'                        => 'Number Prefix',
        'digit'                         => 'Number Digit',
        'next'                          => 'Next Number',
        'logo'                          => 'Logo',
        'custom'                        => 'Custom',
        'item_name'                     => 'Item Name',
        'item'                          => 'Items',
        'product'                       => 'Products',
        'service'                       => 'Services',
        'price_name'                    => 'Price Name',
        'price'                         => 'Price',
        'rate'                          => 'Rate',
        'quantity_name'                 => 'Quantity Name',
        'quantity'                      => 'Quantity',
        'payment_terms'                 => 'Payment Terms',
        'title'                         => 'Title',
        'subheading'                    => 'Subheading',
        'due_receipt'                   => 'Due upon receipt',
        'due_days'                      => 'Due within :days days',
        'due_custom'                    => 'Custom day(s)',
        'due_custom_day'                => 'after day',
        'choose_template'               => 'Choose invoice template',
        'default'                       => 'Default',
        'classic'                       => 'Classic',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Hide Item Name',
            'item_description'          => 'Hide Item Description',
            'quantity'                  => 'Hide Quantity',
            'price'                     => 'Hide Price',
            'amount'                    => 'Hide Amount',
        ],
        'column'                        => 'Column|Columns',

        'form_description' => [
            'general'                   => 'Set the defaults for formatting your invoice numbers and payment terms.',
            'template'                  => 'Select one of the templates below for your invoices.',
            'default'                   => 'Selecting defaults for invoices will pre-populate titles, subheadings, notes, and footers. So you don\'t need to edit invoices each time to look more professional.',
            'column'                    => 'Customize how the invoice columns are named. If you like to hide item descriptions and amounts in lines, you can change it here.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Choose transfer template',
        'second'                        => 'Second',
        'third'                         => 'Third',
    ],

    'default' => [
        'description'                   => 'Default account, currency, language of your company',
        'search_keywords'               => 'account, currency, language, tax, payment, method, pagination',
        'list_limit'                    => 'Records Per Page',
        'use_gravatar'                  => 'Use Gravatar',
        'income_category'               => 'Income Category',
        'expense_category'              => 'Expense Category',
        'address_format'                => 'Address Format',
        'address_tags'                  => '<strong>Available Tags:</strong> :tags',

        'form_description' => [
            'general'                   => 'Select the default account, tax, and payment method to create records swiftly. Dashboard and Reports are shown under the default currency.',
            'category'                  => 'Select the default categories to expedite the record creation.',
            'other'                     => 'Customize the default settings of the company language and how pagination works. ',
        ],
    ],

    'email' => [
        'description'                   => 'Change the sending protocol',
        'search_keywords'               => 'email, send, protocol, smtp, host, password',
        'protocol'                      => 'Protocol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Host',
            'port'                      => 'SMTP Port',
            'username'                  => 'SMTP Username',
            'password'                  => 'SMTP Password',
            'encryption'                => 'SMTP Security',
            'none'                      => 'None',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail Path',
        'log'                           => 'Log Emails',
        'email_service'                 => 'Email Service',
        'email_templates'               => 'Email Templates',

        'form_description' => [
            'general'                   => 'Send regular emails to your team and contacts. You can set the protocol and SMTP settings.',
        ],

        'templates' => [
            'description'               => 'Change the email templates',
            'search_keywords'           => 'email, template, subject, body, tag',
            'subject'                   => 'Subject',
            'body'                      => 'Body',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_view_admin'        => 'Invoice View Template (sent to admin)',
            'invoice_payment_customer'  => 'Invoice Payment Receipt Template (sent to customer)',
            'invoice_payment_admin'     => 'Invoice Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
            'payment_received_customer' => 'Payment Receipt Template (sent to customer)',
            'payment_made_vendor'       => 'Payment Made Template (sent to vendor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Scheduling',
        'description'                   => 'Automatic reminders and command for recurring',
        'search_keywords'               => 'automatic, reminder, recurring, cron, command',
        'send_invoice'                  => 'Send Invoice Reminder',
        'invoice_days'                  => 'Send After Due Days',
        'send_bill'                     => 'Send Bill Reminder',
        'bill_days'                     => 'Send Before Due Days',
        'cron_command'                  => 'Cron Command',
        'command'                       => 'Command',
        'schedule_time'                 => 'Hour To Run',

        'form_description' => [
            'invoice'                   => 'Enable or disable, and set reminders for your invoices when they are overdue.',
            'bill'                      => 'Enable or disable, and set reminders for your bills before they are overdue.',
            'cron'                      => 'Copy the cron command that your server should run. Set the time to trigger the event.',
        ]
    ],

    'categories' => [
        'description'                   => 'Unlimited categories for income, expense, and item',
        'search_keywords'               => 'category, income, expense, item',
    ],

    'currencies' => [
        'description'                   => 'Create and manage currencies and set their rates',
        'search_keywords'               => 'default, currency, currencies, code, rate, symbol, precision, position, decimal, thousands, mark, separator',
    ],

    'taxes' => [
        'description'                   => 'Fixed, normal, inclusive, and compound tax rates',
        'search_keywords'               => 'tax, rate, type, fixed, inclusive, compound, withholding',
    ],

];
