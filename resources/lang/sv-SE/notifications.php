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

];
