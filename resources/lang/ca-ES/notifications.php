<?php

return [

    'whoops'              => 'Whoops!',
    'hello'               => 'Hola!',
    'salutation'          => 'Atentament,<br> :company_name',
    'subcopy'             => 'Si tens problemes quan prems el botó ":text", copia i enganxa l\'enllaç de sota al teu navegador: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ L\'actualització a :domain ha fallat',
            'message' => '
L\'actualització de :alias des de :current_version a :new_version ha fallat al pas <strong>:step</strong> amb el següent missatge d\'error: :error_message',

        ],

        'slack' => [

            'message' => 'L\'actualització ha fallat a :domain',

        ],

    ],

];
