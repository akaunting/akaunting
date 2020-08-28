<?php

return [

    'company' => [
        'description'       => 'Change company name, email, address, tax number etc',
        'name'              => 'Nafn',
        'email'             => 'Tölvupóstur',
        'phone'             => 'Sími',
        'address'           => 'Heimilisfang',
        'logo'              => 'Firmamerki',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
        'financial_start'   => 'Bókhaldsárið byrjar',
        'timezone'          => 'Tímabelti',
        'date' => [
            'format'        => 'Dagsetningasnið',
            'separator'     => 'Texta aðskiljari',
            'dash'          => 'Stirk (-)',
            'dot'           => 'Punktur (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Skástrik (/)',
            'space'         => 'Bil ( )',
        ],
        'percent' => [
            'title'         => 'Prósent (%) Staðsetning',
            'before'        => 'Á undan númeri',
            'after'         => 'Á eftir númeri',
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
        'prefix'            => 'Formerki númers',
        'digit'             => 'Brot',
        'next'              => 'Næsta númer',
        'logo'              => 'Firmamerki',
        'custom'            => 'Sérsniðið',
        'item_name'         => 'Lýsing',
        'item'              => 'Magn',
        'product'           => 'Vörur',
        'service'           => 'Þjónustur',
        'price_name'        => 'Nafn verðs',
        'price'             => 'Verð',
        'rate'              => 'Gengi',
        'quantity_name'     => 'Eining',
        'quantity'          => 'Magn',
        'payment_terms'     => 'Payment Terms',
        'title'             => 'Title',
        'subheading'        => 'Subheading',
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
        'choose_template'   => 'Choose invoice template',
        'default'           => 'Default',
        'classic'           => 'Classic',
        'modern'            => 'Modern',
    ],

    'default' => [
        'description'       => 'Default account, currency, language of your company',
        'list_limit'        => 'Records Per Page',
        'use_gravatar'      => 'Use Gravatar',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
        'protocol'          => 'Aðferð',
        'php'               => 'PHP-póstur',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP hýsing',
            'port'          => 'SMTP Tengi',
            'username'      => 'SMTP-notandanafn',
            'password'      => 'SMTP lykilorð',
            'encryption'    => 'SMTP öryggi',
            'none'          => 'Engin',
        ],
        'sendmail'          => 'Senda tölvupóst',
        'sendmail_path'     => 'Senda tölvupóst slóð',
        'log'               => 'Skrá tölvupósta',

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
        ],
    ],

    'scheduling' => [
        'name'              => 'Scheduling',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'Senda reiknings áminningur',
        'invoice_days'      => 'Senda eftir gjalddaga',
        'send_bill'         => 'Senda reiknings áminningu',
        'bill_days'         => 'Senda fyrir gjalddaga',
        'cron_command'      => 'Endurtekin (CRON) aðgerð',
        'schedule_time'     => 'Klukkutímar að keyra',
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
