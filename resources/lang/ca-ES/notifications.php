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

    'import' => [

        'completed' => [
            'subject'           => 'Importació completada',
            'description'       => 'S\'ha completat la importació i els registres ja estan disponibles al teu tauler.',
        ],

        'failed' => [
            'subject'           => 'Ha fallat la importació.',
            'description'       => 'No s\'ha pogut importar el fitxer a causa de:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'L\'exportació està disponible.',
            'description'       => 'Pots descarregar el fitxer d\'exportació prement el següent enllaç:',
        ],

        'failed' => [
            'subject'           => 'Ha fallat l\'exportació',
            'description'       => 'No s\'ha pogut crear el fitxer d\'exportació a causa de:',
        ],

    ],

];
