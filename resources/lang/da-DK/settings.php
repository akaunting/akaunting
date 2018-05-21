<?php

return [

    'company' => [
        'name'              => 'Navn',
        'email'             => 'E-mail',
        'phone'             => 'Telefon',
        'address'           => 'Adresse',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Lokalisering',
        'date' => [
            'format'        => 'Datoformat',
            'separator'     => 'Datoseparator',
            'dash'          => 'Bindestreg (-)',
            'dot'           => 'Punktum (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Skråstreg (/)',
            'space'         => 'Mellemrum ( )',
        ],
        'timezone'          => 'Tidszone',
        'percent' => [
            'title'         => 'Procent (%) Position',
            'before'        => 'Før nummer',
            'after'         => 'Efter nummer',
        ],
    ],
    'invoice' => [
        'tab'               => 'Faktura',
        'prefix'            => 'Nummerpræfiks',
        'digit'             => 'Antal cifre',
        'next'              => 'Næste nummer',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Standarder',
        'account'           => 'Standard konto',
        'currency'          => 'Standardvaluta',
        'tax'               => 'Standard moms procent',
        'payment'           => 'Standardbetalingsmetode',
        'language'          => 'Standardsprog',
    ],
    'email' => [
        'protocol'          => 'Protokol',
        'php'               => 'PHP mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP Brugernavn',
            'password'      => 'SMTP adgangskode',
            'encryption'    => 'SMTP sikkerhed',
            'none'          => 'Ingen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail sti',
        'log'               => 'Log E-mails',
    ],
    'scheduling' => [
        'tab'               => 'Planlægger',
        'send_invoice'      => 'Send faktura påmindelse',
        'invoice_days'      => 'Send efter forfalds dato',
        'send_bill'         => 'Send regningens påmindelse',
        'bill_days'         => 'Send før forfalds dage',
        'cron_command'      => 'Cron kommando',
        'schedule_time'     => 'Timer at køre',
    ],
    'appearance' => [
        'tab'               => 'Udseende',
        'theme'             => 'Theme',
        'light'             => 'Lys',
        'dark'              => 'Mørk',
        'list_limit'        => 'Poster pr. side',
        'use_gravatar'      => 'Bruge Gravatar',
    ],
    'system' => [
        'tab'               => 'System',
        'session' => [
            'lifetime'      => 'Session levetid (minutter)',
            'handler'       => 'Sessionsbehandler',
            'file'          => 'Fil',
            'database'      => 'Database',
        ],
        'file_size'         => 'Maximum filstørrelse (MB)',
        'file_types'        => 'Tilladte filtyper',
    ],

];
