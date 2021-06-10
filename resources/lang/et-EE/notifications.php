<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Tere!',
    'salutation'          => 'Parimate soovidega,<br> :company_name',
    'subcopy'             => 'Kui teil on probleeme lingile ":text" klõpsamisega, kopeerige ja kleepige allolev URL oma veebibrauserisse: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Värskendus ebaõnnestus :domain',
            'message' => 'Värskendus :alias :current_version :new_version nurjus <strong>:step</strong> alljärgneva teatega: :error_message',

        ],

        'slack' => [

            'message' => 'Värskendus ebaõnnestus :domain',

        ],

    ],

    'import' => [

        'completed' => [
            'subject'           => 'Importimine lõpetatud',
            'description'       => 'Import on lõpule viidud ja kirjed on teie kuvaril saadaval.',
        ],

        'failed' => [
            'subject'           => 'Import ebaõnnestus',
            'description'       => 'Faili ei saa importida järgmiste probleemide tõttu:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'Export on valmis',
            'description'       => 'Ekspordifail on allalaadimiseks valmis järgmiselt lingilt:',
        ],

        'failed' => [
            'subject'           => 'Export ebaõnnestus',
            'description'       => 'Ekspordifaili ei saa luua järgmise probleemi tõttu:',
        ],

    ],

];
