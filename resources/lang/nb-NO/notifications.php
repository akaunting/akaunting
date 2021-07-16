<?php

return [

    'whoops'              => 'Uff!',
    'hello'               => 'God dag!',
    'salutation'          => 'Vennlig hilsen<br> :company_name',
    'subcopy'             => 'Hvis du har problememer med å klikke på ":text" knappen, vennligst kopier følgende URL til din nettleser: [:url](:url)',
    'reads'               => 'Les|Les',
    'read_all'            => 'Les alle',
    'mark_read'           => 'Merk som lest',
    'mark_read_all'       => 'Merk alt som lest',
    'new_apps'            => 'Ny app|Nye apper',
    'upcoming_bills'      => 'Kommende fakturaer',
    'recurring_invoices'  => 'Periodiske fakturaer',
    'recurring_bills'     => 'Gjentakende fakturaer',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Oppdatering feilet på :domain',
            'message' => 'Oppdateringen av :alias fra :current_version til :new_version feilet i <strong>:step</strong> trinn med følgende melding: :error_message',

        ],

        'slack' => [

            'message' => 'Oppdatering feilet på :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Import fullført',
            'description'       => 'Importen ble fullført og postene er tilgjengelige i ditt panel.',

        ],

        'failed' => [

            'subject'           => 'Import mislyktes',
            'description'       => 'Kunne ikke importere filen på grunn av følgende problemer:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Eksporten er klar',
            'description'       => 'Eksportfilen er klar til å lastes ned fra følgende lenke:',

        ],

        'failed' => [

            'subject'           => 'Eksportering feilet',
            'description'       => 'Kunne ikke opprette eksportfilen på grunn av følgende problem:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type er les denne varselen!',
        'mark_read_all'         => ':type er les alle varsler!',
        'new_app'               => ':type app publisert.',
        'export'                => 'Din <b>:type</b> eksportfil er klar til <a href=":url" target="_blank"><b>nedlasting</b></a>.',
        'import'                => 'Din <b>:type</b> linje <b>:count</b> data er velykket importert.',

    ],
];
