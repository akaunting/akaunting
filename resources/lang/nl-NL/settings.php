<?php

return [

    'company' => [
        'description'       => 'Change company name, email, address, tax number etc',
        'name'              => 'Naam',
        'email'             => 'E-mail',
        'phone'             => 'Telefoonnummer',
        'address'           => 'Adres',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
        'financial_start'   => 'Start financieel boekjaar',
        'timezone'          => 'Tijdzone',
        'date' => [
            'format'        => 'Datum formaat',
            'separator'     => 'Datumscheidingsteken',
            'dash'          => 'Streepje (-)',
            'dot'           => 'Punt (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spatie ( ) ',
        ],
        'percent' => [
            'title'         => 'Procent (%) Positie',
            'before'        => 'Voor aantal',
            'after'         => 'Na aantal',
        ],
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'Nummer voorvoegsel',
        'digit'             => 'Aantal cijfers',
        'next'              => 'Volgende nummer',
        'logo'              => 'Logo',
        'custom'            => 'Aangepast',
        'item_name'         => 'Item Naam',
        'item'              => 'Artikel|Artikelen',
        'product'           => 'Producten',
        'service'           => 'Diensten',
        'price_name'        => 'Prijs label',
        'price'             => 'Prijs',
        'rate'              => 'Tarief',
        'quantity_name'     => 'Hoeveelheid label',
        'quantity'          => 'Aantal',
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
        'protocol'          => 'Protocol',
        'php'               => 'PHP mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP host',
            'port'          => 'SMTP-poort',
            'username'      => 'SMTP gebruikersnaam',
            'password'      => 'SMTP wachtwoord',
            'encryption'    => 'SMTP beveiliging',
            'none'          => 'Geen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail pad',
        'log'               => 'E-mail logs',

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
        'send_invoice'      => 'Factuur herinnering sturen',
        'invoice_days'      => 'Aantal dagen na vervaldatum sturen',
        'send_bill'         => 'Factuur herinnering sturen',
        'bill_days'         => 'Aantal dagen voor vervaldatum sturen',
        'cron_command'      => 'Cron opdracht',
        'schedule_time'     => 'Uren duurtijd',
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
