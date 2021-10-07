<?php

return [

    'whoops'              => 'Oeps!',
    'hello'               => 'Hallo!',
    'salutation'          => 'Met vriendelijke groet,<br>:company_name',
    'subcopy'             => 'Als u problemen ondervindt met het klikken op ":text" knop, kopieer en plak dan onderstaande URL in uw webbrowser: [:url](:url)',
    'reads'               => 'Lees|Lezen',
    'read_all'            => 'Lees alles',
    'mark_read'           => 'Markeer als gelezen',
    'mark_read_all'       => 'Markeer alles als gelezen',
    'new_apps'            => 'Nieuwe App|Nieuwe Apps',
    'upcoming_bills'      => 'Aankomende rekeningen',
    'recurring_invoices'  => 'Terugkerende facturen',
    'recurring_bills'     => 'Terugkerende rekeningen',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Update mislukt op :domain',
            'message' => 'De update van :alias van :current_version naar :new_version is mislukt in <strong>:step</strong> stap met het volgende bericht: :error_message',

        ],

        'slack' => [

            'message' => 'Update mislukt op :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Importeren voltooid',
            'description'       => 'De import is voltooid en de records zijn beschikbaar in uw paneel.',

        ],

        'failed' => [

            'subject'           => 'Importeren mislukt',
            'description'       => 'Niet in staat om het bestand te importeren vanwege de volgende problemen:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Export is klaar',
            'description'       => 'Het exportbestand is klaar om te downloaden van de volgende link:',

        ],

        'failed' => [

            'subject'           => 'Exporteren mislukt',
            'description'       => 'Niet in staat om het exportbestand aan te maken vanwege het volgende probleem:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type lees deze notificatie!',
        'mark_read_all'         => ':type lees deze notificaties!',
        'new_app'               => ':type app gepubliceerd.',
        'export'                => 'Uw <b>:type</b> exportbestand is klaar om <a href=":url" target="_blank"><b>te downloaden</b></a>.',
        'import'                => 'Uw <b>:type</b> regel <b>:count</b> is succesvol geïmporteerd.',

    ],
];
