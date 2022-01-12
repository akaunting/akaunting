<?php

return [

    'whoops'              => 'Jejda!',
    'hello'               => 'Ahoj!',
    'salutation'          => 'S pozdravem,<br> :company_name',
    'subcopy'             => 'Pokud vám nefunguje tlačítko ":text", zkopírujte a vložte adresu URL do prohlížeče: [:url](:url)',
    'reads'               => 'Číst|Číst',
    'read_all'            => 'Číst vše',
    'mark_read'           => 'Označit přečtené',
    'mark_read_all'       => 'Označit všechny přečtené',
    'new_apps'            => 'Nová aplikace|Nové aplikace',
    'upcoming_bills'      => 'Nadcházející účty',
    'recurring_invoices'  => 'Opakující se faktury',
    'recurring_bills'     => 'Opakující se účty',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Aktualizace selhala na :domain',
            'message' => 'Aktualizace :alias z :current_version na :new_version selhala v <strong>:step</strong> kroku s následující zprávou: :error_message',

        ],

        'slack' => [

            'message' => 'Aktualizace se nezdařila na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Import dokončen',
            'description'       => 'Import byl dokončen a záznamy jsou k dispozici ve vašem panelu.',

        ],

        'failed' => [

            'subject'           => 'Import se nezdařil',
            'description'       => 'Není možné importovat soubor z důvodu následujících důvodů:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Export je připraven',
            'description'       => 'Exportovaný soubor je připraven ke stažení z následujícího odkazu:',

        ],

        'failed' => [

            'subject'           => 'Export se nezdařil',
            'description'       => 'Není možné vytvořit exportovaný soubor z důvodu následujícího důvodu:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type přečte toto oznámení!',
        'mark_read_all'         => ':type je načtení všech oznámení!',
        'new_app'               => ':type aplikace byla zveřejněna.',
        'export'                => 'Váš <b>:type</b> exportovaný soubor je připraven ke <a href=":url" target="_blank"><b>stáhnout</b></a>.',
        'import'                => 'Vaše <b>:type</b> vložená data <b>:count</b> jsou úspěšně importována.',

    ],
];
