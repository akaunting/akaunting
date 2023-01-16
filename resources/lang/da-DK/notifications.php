<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Hej!',
    'salutation'          => 'Med venlig hilsen,<br> :company_name',
    'subcopy'             => 'Hvis du har problemer med at klikke på ":text" knappen, kopier og indsæt nedenstående URL i din webbrowser: [:url](:url)',
    'mark_read'           => 'Marker som læst',
    'mark_read_all'       => 'Marker alle som læste',
    'empty'               => 'Woohoo, ingen notifikationer!',
    'new_apps'            => 'Ny app|Nye apps
',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Opdatering mislykkedes på :domain',
            'description'   => 'Opdateringen af :alias fra :current_version til :new_version fejlede i <strong>:step</strong> trin med følgende meddelelse: error_message',

        ],

        'slack' => [

            'description'   => 'Opdatering mislykkedes på :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Import fuldført',
            'description'   => 'Importen er gennemført, og posteringerne er tilgængelige i dit panel.',

        ],

        'failed' => [

            'title'         => 'Import mislykkedes',
            'description'   => 'Ikke i stand til at importere filen på grund af følgende problemer:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Eksport er klar',
            'description'   => 'Eksportfilen er klar til download fra følgende link:',

        ],

        'failed' => [

            'title'         => 'Eksport mislykkedes',
            'description'   => 'Ikke i stand til at oprette eksportfilen på grund af følgende problem:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Eksport er klar',
            'description'   => 'Din <strong>:type</strong> eksportfil er klar til <a href=":url" target="_blank"><strong>download</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Eksport mislykkedes',
            'description'   => 'Ikke i stand til at oprette eksportfilen på grund af flere problemer. Tjek din e-mail for detaljerne.',

        ],

        'import_completed' => [

            'title'         => 'Import fuldført',
            'description'   => 'Dine <b>:type</b> linje <b>:count</b> data er importeret.',

        ],

        'import_failed' => [

            'subject'       => 'Import mislykkedes',
            'description'   => 'Ikke i stand til at importere filen på grund af flere problemer. Tjek din e-mail for detaljerne.',

        ],

        'new_apps' => [

            'title'         => 'Ny app',
            'description'   => '<strong>:name</strong> app er ude. Du kan <a href=":url">klikke her</a> for at se detaljerne.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Ny faktura',
            'description'   => '<strong>:invoice_number</strong> faktura er oprettet. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne og fortsætte med betalingen.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Faktura Forfalden',
            'description'   => '<strong>:invoice_number</strong> faktura forfaldt <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne og fortsætte med betalingen.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Faktura Forfalden',
            'description'   => '<strong>:invoice_number</strong> faktura forfaldt <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne og fortsætte med betalingen.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Ny tilbagevendende faktura',
            'description'   => '<strong>:invoice_number</strong> faktura oprettes baseret på din tilbagevendende cirkel. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne og fortsætte med betalingen.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Ny tilbagevendende faktura',
            'description'   => '<strong>:invoice_number</strong> faktura oprettes baseret på <strong>:customer_name</strong> tilbagevendende cirkel. Du kan <a href=":invoice_admin_link">klikke her</a> for at se detaljerne.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktura set',
            'description'   => '<strong>:customer_name</strong> har set faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klikke her</a> for at se detaljerne.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Betaling modtaget',
            'description'   => 'Tak for betalingen for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Betaling modtaget',
            'description'   => 'Tak for betalingen for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for at se detaljerne.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Betaling modtaget',
            'description'   => ':customer_name registrerede betaling for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klikke her</a> for at se detaljerne.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Forfalden faktura',
            'description'   => '<strong>:bill_number</strong> regning var forfalden <strong>:bill_due_date</strong>. Du kan <a href=":bill_admin_link">klikke her</a> for at se detaljerne.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Tilbagevendende regninger',
            'description'   => 'Regning <strong>:bill_number</strong> er oprettet baseret på <strong>:vendor_name</strong> tilbagevendende cirkel. Du kan <a href=":bill_admin_link">klikke her</a> for at se detaljerne.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type læs denne notifikation',
        'mark_read_all'         => ':type læs alle notifikationerne',

    ],
];
