<?php

return [

    'whoops'              => 'Oops!',
    'hello'               => 'Përshëndetje!',
    'salutation'          => 'Respekte,<br>:company_name',
    'subcopy'             => 'Nëse ke probleme duke klikuar butonin ":text", kopjoni dhe ngjisni URL më poshtë në shfletuesin tuaj të internetit: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Përditësimi dështoi në :domain',
            'message' => 'Azhurnimi i :alias nga :current_version në :new_version dështoi në hapin <strong>:step</strong> me mesazhin vijues: :error_message',

        ],

        'slack' => [

            'message' => 'Përditësimi dështoi në :domain',

        ],

    ],

    'import' => [

        'completed' => [
            'subject'           => 'Importi ka përfunduar',
            'description'       => 'Importi ka përfunduar dhe të dhënat janë në dispozicion në panelin tuaj.',
        ],

        'failed' => [
            'subject'           => 'Importi dështoi.',
            'description'       => 'Nuk është në gjendje të importojë skedarin për shkak të çështjeve të mëposhtme:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'Eksporti është gati',
            'description'       => 'Skedari i eksportit është gati për t\'u shkarkuar nga linku vijues:',
        ],

        'failed' => [
            'subject'           => 'Eksporti dështoi',
            'description'       => 'Nuk është në gjendje të krijojë skedarin e eksportit për shkak të çështjes vijuese:',
        ],

    ],

];
