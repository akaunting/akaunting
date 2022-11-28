<?php

return [

    'whoops'              => 'Hoppsan!',
    'hello'               => 'Hallå!',
    'salutation'          => 'Hälsningar,<br> :company_name',
    'subcopy'             => 'Om du har problem att klicka på den ”:text”-knappen, kopiera och klistra in webbadressen nedan i din webbläsare: [:url](:url)',
    'mark_read'           => 'Markera som läst',
    'mark_read_all'       => 'Markera som läst alla',
    'empty'               => 'Woohoo, inga fler notiser!',
    'new_apps'            => 'Ny app|Nya appar',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Uppdatering misslyckades för :domain',
            'description'   => 'Uppdateringen av :alias från :current_version till :new_version misslyckades i steg  <strong>:step</strong>, med följande meddelande: :error_message',

        ],

        'slack' => [

            'description'   => 'Uppdatering misslyckades för :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importen slutförd',
            'description'   => 'Importen har slutförts och posterna finns i din panel.',

        ],

        'failed' => [

            'title'         => 'Importen misslyckades',
            'description'   => 'Kan inte importera filen på grund av följande problem:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Exporten är klar',
            'description'   => 'Exportfilen är klar att ladda ner från följande länk:',

        ],

        'failed' => [

            'title'         => 'Exporten misslyckades',
            'description'   => 'Kan inte skapa exportfilen på grund av följande problem:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Exporten är klar',
            'description'   => 'Din <b>:type</b> exportfil är redo att <a href=":url" target="_blank"><b>laddas ner</b></a>.',

        ],

        'export_failed' => [

            'title'         => 'Exporten misslyckades',
            'description'   => 'Kan inte skapa exportfilen på grund av flera problem. Kolla in din e-post för detaljerna.',

        ],

        'import_completed' => [

            'title'         => 'Importen slutförd',
            'description'   => 'Din <b>:type</b> med <b>:count</b> datalinjer importerades framgångsrikt.',

        ],

        'import_failed' => [

            'subject'       => 'Importen misslyckades',
            'description'   => 'Kan inte importera filen på grund av flera problem. Kolla in din e-post för detaljerna.',

        ],

        'new_apps' => [

            'title'         => 'Ny App',
            'description'   => 'Ny version av appen <strong>:name</strong> har släppts. Du kan <a href=":url">klicka här</a> för att se detaljerna.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Ny faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> har skapats. Du kan <a href=":invoice_portal_link">klicka här</a> för att se detaljerna och fortsätta med betalningen.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Förfallen faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> förföll <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_portal_link">klicka här</a> för att se detaljerna och fortsätta med betalningen.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Förfallen faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> förföll  <strong>:invoice_due_date</strong>. Du kan <a href=":invoice_admin_link">klicka här</a> för detaljerna.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Ny återkommande faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> har upprättats baserat på ditt återkommande schema. Du kan <a href=":invoice_portal_link">klicka här</a> för att se detaljerna och fortsätta med betalningen.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Ny återkommande faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> har upprättats baserat på det återkommande schemat för <strong>:customer_name</strong>. Du kan <a href=":invoice_admin_link">klicka här</a> för att se detaljerna.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktura visad',
            'description'   => '<strong>:customer_name</strong> har sett faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klicka här</a> för att se detaljerna.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Betalning mottagen',
            'description'   => 'Tack så mycket, vi har mottagit betalning för faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klicka här</a> för att se detaljerna.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Betalning mottagen',
            'description'   => 'Tack så mycket, vi har mottagit betalning för faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_portal_link">klicka här</a> för att se detaljerna.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Betalning mottagen',
            'description'   => ':customer_name har betalat faktura <strong>:invoice_number</strong>. Du kan <a href=":invoice_admin_link">klicka här</a> för att se detaljerna.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Förfallen räkning',
            'description'   => 'Räkning <strong>:bill_number</strong> förföll <strong>:bill_due_date</strong>. Du kan <a href=":bill_admin_link">klicka här</a> för att se detaljerna.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Ny återkommade räkning',
            'description'   => 'Räkning <strong>:bill_number</strong> har upprättats baserat på det återkommande schemat för <strong>:vendor_name</strong>. Du kan <a href=":bill_admin_link">klicka här</a> för att se detaljerna.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type är läst denna notifiering!',
        'mark_read_all'         => ':type är läst alla notifikeringar!',

    ],
];
