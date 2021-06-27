<?php

return [

    'whoops'              => 'Hoppsan!',
    'hello'               => 'Hallå!',
    'salutation'          => 'Hälsningar,<br> :company_name',
    'subcopy'             => 'Om du har problem att klicka på den ”:text”-knappen, kopiera och klistra in webbadressen nedan i din webbläsare: [:url](:url)',
    'reads'               => 'läsa|läsningar',
    'read_all'            => 'Läs alla',
    'mark_read'           => 'Markera som läst',
    'mark_read_all'       => 'Markera som läst alla',
    'new_apps'            => 'Ny app|Nya appar',
    'upcoming_bills'      => 'Kommande fakturor',
    'recurring_invoices'  => 'Återkommande fakturor',
    'recurring_bills'     => 'Återkommande räkningar',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Uppdatering misslyckades :domain',
            'message' => 'Uppdateringen av :alias från :current_version till :new_version misslyckades i <strong>:step</strong> steg med följande meddelande: :error_message',

        ],

        'slack' => [

            'message' => 'Uppdateringen misslyckades :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Importen slutförd',
            'description'       => 'Importen har slutförts och posterna finns i din panel.',

        ],

        'failed' => [

            'subject'           => 'Importen misslyckades',
            'description'       => 'Kan inte importera filen på grund av följande problem:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Exporteringen är klar',
            'description'       => 'Exportfilen är klar att ladda ner från följande länk:',

        ],

        'failed' => [

            'subject'           => 'Exporteringen misslyckades',
            'description'       => 'Kan inte skapa exportfilen på grund av följande problem:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type är läst denna notifiering!',
        'mark_read_all'         => ':type är läst alla notifikeringar!',
        'new_app'               => ':type app publicerad.',
        'export'                => 'Din <b>:type</b> exportfil är redo att <a href=":url" target="_blank"><b>ladda ner</b></a>.',
        'import'                => 'Din <b>:type</b> fodrad <b>:count</b> data importeras.',

    ],
];
