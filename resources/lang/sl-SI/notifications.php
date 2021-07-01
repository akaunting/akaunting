<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Dobrodošli!',
    'salutation'          => 'S spoštovanjem,<br> : company_name',
    'subcopy'             => 'Če imate težave s klikom na gumb ": besedilo", kopirajte in prilepite spodnji URL v spletni brskalnik: [: url](:url)',
    'reads'               => 'Preberi|Preberi',
    'read_all'            => 'Preberi vse',
    'mark_read'           => 'Označi kot prebrano',
    'mark_read_all'       => 'Označi vse kot prebrano',
    'new_apps'            => 'Nova aplikacija|Novih aplikacij',
    'upcoming_bills'      => 'Prihajajoči računi',
    'recurring_invoices'  => 'Ponavljajoči se računi',
    'recurring_bills'     => 'Ponavljajoči se računi',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Posodobitev neuspešna na :domain',
            'message' => 'Posodobitev :alias iz različice :current_version na :new_version je neuspašna na <strong>:step</strong> koraku z naslednjim sporočilom: :error_message',

        ],

        'slack' => [

            'message' => 'Posodobitev neuspešna na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Uvoz je končan',
            'description'       => 'Uvoz je končan in zapisi so na voljo na nadzorni plošči.',

        ],

        'failed' => [

            'subject'           => 'Uvoz ni uspel',
            'description'       => 'Datoteke ni mogoče uvoziti zaradi težave:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Izvoz je pripravljen',
            'description'       => 'Izvozna datoteka je pripravljena za prenos s povezave:',

        ],

        'failed' => [

            'subject'           => 'Izvoz ni uspel',
            'description'       => 'Izvozne datoteke ni bilo mogoče ustvariti zaradi težave:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type je prebral to obvestilo!',
        'mark_read_all'         => ':type je prebral vsa obvestila!',
        'new_app'               => ':type aplikacija objavljena.',
        'export'                => 'Izvozna datoteka <b>:type</b> je na voljo za <a href=":url" target="_blank"><b>prenos</b></a>.',
        'import'                => 'Vaši <b>:type</b> podloženi <b>:count</b> podatki so uspešno uvoženi.',

    ],
];
