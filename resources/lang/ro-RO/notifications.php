<?php

return [

    'whoops'              => 'Ooops!',
    'hello'               => 'Buna!',
    'salutation'          => 'Salutari,<br>:company_name',
    'subcopy'             => 'Daca nu poti face click pe butonul ":text", copiaza si lipeste link-ul de mai jos in browser: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Actualizarea a eșuat pe :domain',
            'message' => 'Actualizarea :alias de la :current_version la :new_version a eșuat în pasul <strong>:step</strong> cu următorul mesaj: :error_message',

        ],

        'slack' => [

            'message' => '⚠️ Actualizarea a eșuat pe :domain',

        ],

    ],

    'import' => [

        'completed' => [
            'subject'           => 'Importul a fost finalizat',
            'description'       => 'Importul a fost completat și înregistrările sunt disponibile în panoul dvs.',
        ],

        'failed' => [
            'subject'           => 'Importul a eșuat',
            'description'       => 'Nu se poate importa fişierul din cauza următoarelor probleme:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'Exportul este gata',
            'description'       => 'Fișierul de export este gata pentru descărcare de la următorul link:',
        ],

        'failed' => [
            'subject'           => 'Exportul a eșuat',
            'description'       => 'Nu se poate exporta fişierul din cauza următoarelor probleme:',
        ],

    ],

];
