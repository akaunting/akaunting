<?php

return [

    'company' => [
        'name'              => 'Vārds',
        'email'             => 'E-pasts',
        'phone'             => 'Tālrunis',
        'address'           => 'Adrese',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Reģionālie iestatījumi',
        'date' => [
            'format'        => 'Datuma formāts',
            'separator'     => 'Datuma atdalītājs',
            'dash'          => 'Domuzīme (-)',
            'dot'           => 'Punkts (.)',
            'comma'         => 'Komats (,)',
            'slash'         => 'Daļsvītra (/)',
            'space'         => 'Atstarpe ( )',
        ],
        'timezone'          => 'Laika zona',
        'percent' => [
            'title'         => 'Procentu (%) pozīcija',
            'before'        => 'Pirms skaitļa',
            'after'         => 'Pēc skaitļa',
        ],
    ],
    'invoice' => [
        'tab'               => 'Rēķini',
        'prefix'            => 'Rēķina sākums',
        'digit'             => 'Rēķina numura garums',
        'next'              => 'Nākamais numurs',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Noklusētie iestatījumi',
        'account'           => 'Noklusētais konts',
        'currency'          => 'Noklusētā valūta',
        'tax'               => 'Noklusētā PVN likme',
        'payment'           => 'Noklusētā maksājuma metode',
        'language'          => 'Noklusētā valoda',
    ],
    'email' => [
        'protocol'          => 'Protokols',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP adrese',
            'port'          => 'SMTP ports',
            'username'      => 'SMTP lietotājs',
            'password'      => 'SMTP parole',
            'encryption'    => 'SMTP drošība',
            'none'          => 'nav',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail ceļš',
        'log'               => 'Auditēt e-pastus',
    ],
    'scheduling' => [
        'tab'               => 'Atgādinājumi',
        'send_invoice'      => 'Sūtīt rēķinu atgādinājumus',
        'invoice_days'      => 'Sūtīt pēc kavētām dienām',
        'send_bill'         => 'Sūtīt piegādātāju rēķinu atgādinājumus',
        'bill_days'         => 'Sūtīt dienas pirms termiņa',
        'cron_command'      => 'Cron komanda',
        'schedule_time'     => 'Stunda kurā sūtīt',
    ],
    'appearance' => [
        'tab'               => 'Izskats',
        'theme'             => 'Tēma',
        'light'             => 'Gaiša',
        'dark'              => 'Tumša',
        'list_limit'        => 'Ieraksti lapā',
        'use_gravatar'      => 'Lietot attēlu',
    ],
    'system' => [
        'tab'               => 'Sistēma',
        'session' => [
            'lifetime'      => 'Sesijas ilgums (minūtes)',
            'handler'       => 'Sesijas uzturēšana',
            'file'          => 'Fails',
            'database'      => 'Datubāze',
        ],
        'file_size'         => 'Maksimālais faila lielums (MB)',
        'file_types'        => 'Atļautie failu tipi',
    ],

];
