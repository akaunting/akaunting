<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Hej!',
    'salutation'          => 'Med venlig hilsen,<br> :company_name',
    'subcopy'             => 'Hvis du har problemer med at klikke på ":text" knappen, kopier og indsæt nedenstående URL i din webbrowser: [:url](:url)',
    'reads'               => 'Læst|Læsninger',
    'read_all'            => 'Læs alle',
    'mark_read'           => 'Marker som læst',
    'mark_read_all'       => 'Marker alle som læste',
    'new_apps'            => 'Ny app|Nye apps
',
    'upcoming_bills'      => 'Kommende regninger',
    'recurring_invoices'  => 'Tilbagevendende fakturaer',
    'recurring_bills'     => 'Tilbagevendende regninger',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Opdatering mislykkedes på :domain',
            'message' => 'Opdateringen af :alias fra :current_version til :new_version fejlede i <strong>:step</strong> trin med følgende meddelelse: error_message',

        ],

        'slack' => [

            'message' => 'Opdatering mislykkedes på :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Import fuldført',
            'description'       => 'Importen er gennemført, og posteringerne er tilgængelige i dit panel.',

        ],

        'failed' => [

            'subject'           => 'Import mislykkedes',
            'description'       => 'Ikke i stand til at importere filen på grund af følgende problemer:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Eksport er klar',
            'description'       => 'Eksportfilen er klar til download fra følgende link:',

        ],

        'failed' => [

            'subject'           => 'Eksport mislykkedes',
            'description'       => 'Ikke i stand til at oprette eksportfilen på grund af følgende problem:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type læs denne notifikation',
        'mark_read_all'         => ':type læs alle notifikationerne',
        'new_app'               => ':type app publiceret.',
        'export'                => 'Din <b>:type</b> eksportfil er klar til <a href=":url" target="_blank"><b>download</b></a>.',
        'import'                => 'Dine <b>:type</b> linje <b>:count</b> data er importeret.',

    ],
];
