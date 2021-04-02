<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Hej!',
    'salutation'          => 'Med venlig hilsen,<br> : company_name',
    'subcopy'             => 'Hvis du har problemer med at klikke på ":text" knappen, kopier og indsæt nedenstående URL i din webbrowser: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Opdatering mislykkedes på :domain',
            'message' => 'Opdateringen af :alias fra :current_version til :new_version fejlede i <strong>:step</strong> trin med følgende meddelelse: error_message',

        ],

        'slack' => [

            'message' => 'Opdatering mislykkedes på :domain',

        ],

    ],

];
