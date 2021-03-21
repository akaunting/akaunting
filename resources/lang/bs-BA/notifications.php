<?php

return [

    'whoops'              => 'Uuups!',
    'hello'               => 'Pozdrav!',
    'salutation'          => 'Pozdrav, <br>:company_name',
    'subcopy'             => 'Ako imate problema s klikom na gumb ":text", kopirajte i zalijepite URL ispod u svoj web preglednik: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => 'Azuriranje na novu verziju nije uspjelo na :domain',
            'message' => 'Azuriranje sa :alias sa :current_version na :new_version nije usjelo u <strong>:step</strong> koraku sa slijedećom greškom: :error_message',

        ],

        'slack' => [

            'message' => 'Azuriranje na novu verziju nije uspjelo na :domain',

        ],

    ],

];
