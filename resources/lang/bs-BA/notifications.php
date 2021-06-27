<?php

return [

    'whoops'              => 'Uuups!',
    'hello'               => 'Pozdrav!',
    'salutation'          => 'Pozdrav, <br>:company_name',
    'subcopy'             => 'Ako imate problema s klikom na gumb ":text", kopirajte i zalijepite URL ispod u svoj web preglednik: [:url](:url)',
    'reads'               => 'Procitaj|Procitaj sve',
    'read_all'            => 'Procitaj sve',
    'mark_read'           => 'Označi kao pročitano',
    'mark_read_all'       => 'Označi sve za pročitano',
    'new_apps'            => 'Nova Aplikacija|Nove aplikacije',
    'upcoming_bills'      => 'Nadolazeći računi',
    'recurring_invoices'  => 'Ponavljajuće fakture',
    'recurring_bills'     => 'Ponavljajuće fakture',

    'update' => [

        'mail' => [

            'subject' => 'Azuriranje na novu verziju nije uspjelo na :domain',
            'message' => 'Azuriranje sa :alias sa :current_version na :new_version nije usjelo u <strong>:step</strong> koraku sa slijedećom greškom: :error_message',

        ],

        'slack' => [

            'message' => 'Azuriranje na novu verziju nije uspjelo na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Uvoz završen',
            'description'       => 'Import je završen i unosi su dostupni na vašoj tabli.',

        ],

        'failed' => [

            'subject'           => 'Uvoz nije završen zbog problema',
            'description'       => 'Nije moguće uraditi import fajla zbog ovih grešaka:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Izvoz završen',
            'description'       => 'Export je završen i spreman za download na ovom linku:',

        ],

        'failed' => [

            'subject'           => 'Uvoz nije završen zbog problema',
            'description'       => 'Nije moguće uraditi export fajla zbog ovih grešaka:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type je pročitano obavještenje',
        'mark_read_all'         => ':type je pročitana sva obavještenja!',
        'new_app'               => ':type aplikacija je objavljena.',
        'export'                => 'Vaš <b>:type</b> export fajla je spreman za <a href=":url" target="_blank"><b>skidanje</b></a>.',
        'import'                => 'Vaš <b>:type</b> sa broje linija <b>:count</b> je uspješno dodan.',

    ],
];
