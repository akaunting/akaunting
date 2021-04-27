<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Hej!',
    'salutation'          => 'Med venlig hilsen,<br> :company_name',
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

];
