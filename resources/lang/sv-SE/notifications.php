<?php

return [

    'whoops'              => 'Hoppsan!',
    'hello'               => 'Hallå!',
    'salutation'          => 'Hälsningar,<br> :company_name',
    'subcopy'             => 'Om du har problem att klicka på den ”:text”-knappen, kopiera och klistra in webbadressen nedan i din webbläsare: [:url](:url)',

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

];
