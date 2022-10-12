<?php

return [

    'whoops'              => 'Uff!',
    'hello'               => 'God dag!',
    'salutation'          => 'Vennlig hilsen<br> :company_name',
    'subcopy'             => 'Hvis du har problememer med å klikke på ":text" knappen, vennligst kopier følgende URL til din nettleser: [:url](:url)',
    'mark_read'           => 'Merk som lest',
    'mark_read_all'       => 'Merk alt som lest',
    'empty'               => 'Woohoo, varsling null!',
    'new_apps'            => ':app er tilgjengelig. <a href=":url">Sjekk den ut nå</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Oppdatering mislyktes på :domain',
            'description'   => 'Oppdateringen av :alias fra :current_version til :new_version mislyktes i <strong>:step</strong> trinn med følgende melding: :error_message',

        ],

        'slack' => [

            'description'   => 'Oppdatering mislyktes på :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Import fullført',
            'description'   => 'Importen ble fullført og postene er tilgjengelige i ditt panel.',

        ],

        'failed' => [

            'title'         => 'Import mislyktes',
            'description'   => 'Kunne ikke importere filen på grunn av følgende problemer:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Eksporten er klar',
            'description'   => 'Eksportfilen er klar til å lastes ned fra følgende lenke:',

        ],

        'failed' => [

            'title'         => 'Eksportering mislyktes',
            'description'   => 'Kunne ikke opprette eksportfilen på grunn av følgende problem:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Eksporten er klar',
            'description'   => 'Din <strong>:type</strong> eksportfil er klar til <a href=":url" target="_blank"><strong>nedlasting</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Eksportering mislyktes',
            'description'   => 'Kunne ikke opprette eksportfilen på grunn av flere problemer. Sjekk ut e-posten din for detaljer.',

        ],

        'import_completed' => [

            'title'         => 'Import fullført',
            'description'   => 'Din <strong>:type</strong> med <strong>:count</strong> datalinjer ble importert.',

        ],

        'import_failed' => [

            'subject'       => 'Import mislyktes',
            'description'   => 'Kunne ikke importere filen på grunn av flere problemer. Sjekk ut e-posten din for detaljer.',

        ],

        'new_apps' => [

            'title'         => 'Ny app',
            'description'   => '<strong>:name</strong> appen er avviklet eller har en ny versjon. Du kan <a href=":url">klikke her</a> for å se detaljene.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Ny faktura',
            'description'   => '<strong>:invoice_number</strong> faktura er opprettet. Du kan <a href=":invoice_portal_link">klikk her</a> for å se detaljer og fortsette betalingen.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Faktura forfalt',
            'description'   => 'Faktura <strong>:invoice_number</strong> forfalt <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for å se detaljene og fortsette med betalingen.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Faktura forfalt',
            'description'   => 'Faktura <strong>:invoice_number</strong> forfalt <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_admin_link">klikke her</a> for å se detaljene.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Ny gjentakende faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> opprettes basert på gjentakelsesfrekvensen. Du kan <a href=":invoice_portal_link">klikke her</a> for å se detaljer og fortsette med betalingen.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Ny gjentakende faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> opprettes basert på <strong>:customer_name</strong> sin gjentakelsesfrekvens. Du kan <a href=":invoice_admin_link">klikke her</a> for å se detaljene.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktura vist',
            'description'   => '<strong>:customer_name</strong> har sett faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klikke her</a> for å se detaljene.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Betaling mottatt',
            'description'   => 'Takk for betalingen for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for å se detaljene.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Betaling mottatt',
            'description'   => 'Takk for betalingen for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klikke her</a> for å se detaljene.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Betaling mottatt',
            'description'   => ':customer_name registrerte betaling for faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klikke her</a> for å se detaljene.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Faktura forfalt',
            'description'   => 'Regning <strong>:bill_number</strong> forfalte <strong>:bill_due_date</strong>. Du kan <a href=":bill_admin_link">klikke her</a> for å se detaljene.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Ny gjentagende faktura',
            'description'   => 'Regning <strong>:bill_number</strong> opprettes basert på <strong>:vendor_name</strong> sin gjentakelsesfrekvens. Du kan <a href=":bill_admin_link">klikke her</a> for å se detaljene.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type er les denne varselen!',
        'mark_read_all'         => ':type er les alle varsler!',

    ],
];
