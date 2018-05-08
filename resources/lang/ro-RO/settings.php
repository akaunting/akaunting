<?php

return [

    'company' => [
        'name'              => 'Nume',
        'email'             => 'Email',
        'phone'             => 'Telefon',
        'address'           => 'Adresă',
        'logo'              => 'Siglă',
    ],
    'localisation' => [
        'tab'               => 'Localizare',
        'date' => [
            'format'        => 'Format dată',
            'separator'     => 'Separator data',
            'dash'          => 'Cratimă (-)',
            'dot'           => 'Punct (.)',
            'comma'         => 'Virgulă (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spaţiu ( )',
        ],
        'timezone'          => 'Fus orar',
        'percent' => [
            'title'         => 'Procent (%) Pozitie',
            'before'        => 'Inainte de Numar',
            'after'         => 'Dupa Numar',
        ],
    ],
    'invoice' => [
        'tab'               => 'Factură',
        'prefix'            => 'Prefix',
        'digit'             => 'Zecimale Numar',
        'next'              => 'Următorul număr',
        'logo'              => 'Siglă',
    ],
    'default' => [
        'tab'               => 'Implicit',
        'account'           => 'Cont implicit',
        'currency'          => 'Moneda implicita',
        'tax'               => 'Rata Impozitare Implicita',
        'payment'           => 'Metodă de plată prestabilită',
        'language'          => 'Limba implicita',
    ],
    'email' => [
        'protocol'          => 'Protocol',
        'php'               => 'Mail PHP',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Gazdă SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Utilizator SMTP',
            'password'      => 'Parola SMTP',
            'encryption'    => 'Securitate SMTP',
            'none'          => 'Nici una',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Cale Sendmail',
        'log'               => 'Jurnal Email-uri',
    ],
    'scheduling' => [
        'tab'               => 'Planificare',
        'send_invoice'      => 'Trimite memento pentru factura',
        'invoice_days'      => 'Trimite dupa Zilele Cuvenite',
        'send_bill'         => 'Trimite memento pentru factura',
        'bill_days'         => 'Trimite Inainte de Zilele Cuvenite',
        'cron_command'      => 'Comanda Cron',
        'schedule_time'     => 'Ora la care ruleaza',
    ],
    'appearance' => [
        'tab'               => 'Aspect',
        'theme'             => 'Temă',
        'light'             => 'Deschis',
        'dark'              => 'Inchis',
        'list_limit'        => 'Rezultate pe pagina',
        'use_gravatar'      => 'Foloseste Gravatar',
    ],
    'system' => [
        'tab'               => 'Sistem',
        'session' => [
            'lifetime'      => 'Durata de viaţă a sesiunii (minute)',
            'handler'       => 'Manager Sesiune',
            'file'          => 'Fişier',
            'database'      => 'Bază de date',
        ],
        'file_size'         => 'Mărime maximă a fișierului (MB)',
        'file_types'        => 'Tipuri de fișiere permise',
    ],

];
