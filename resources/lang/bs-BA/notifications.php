<?php

return [

    'whoops'              => 'Uuups!',
    'hello'               => 'Pozdrav!',
    'salutation'          => 'Pozdrav, <br>:company_name',
    'subcopy'             => 'Ako imate problema s klikom na gumb ":text", kopirajte i zalijepite URL ispod u svoj web preglednik: [:url](:url)',
    'mark_read'           => 'Označi kao pročitano',
    'mark_read_all'       => 'Označi sve za pročitano',
    'empty'               => 'Vau, nula obaveštenja!',
    'new_apps'            => ':app je dostupan. <a href=":url">Provjerite sada</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Azuriranje na novu verziju nije uspjelo na :domain',
            'description'   => 'Azuriranje sa :alias sa :current_version na :new_version nije usjelo u <strong>:step</strong> koraku sa slijedećom greškom: :error_message',

        ],

        'slack' => [

            'description'   => '⚠️ Azuriranje na novu verziju nije uspjelo na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Uvoz završen',
            'description'   => 'Import je završen i unosi su dostupni na vašoj tabli.',

        ],

        'failed' => [

            'title'         => 'Uvoz nije uspio',
            'description'   => 'Nije moguće uraditi import fajla zbog ovih grešaka:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Izvoz završen',
            'description'   => 'Export je završen i spreman za download na ovom linku:',

        ],

        'failed' => [

            'title'         => 'Izvoz nije uspio',
            'description'   => 'Nije moguće uraditi export fajla zbog ovih grešaka:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Izvoz završen',
            'description'   => 'Vaš <strong>:type</strong> fajl za izvoz je spreman za <a href=":url" target="_blank"><strong>preuzimanje</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Izvoz nije uspio',
            'description'   => 'Nije moguće kreirati datoteku za izvoz zbog sljedećeg problema: :issues',

        ],

        'import_completed' => [

            'title'         => 'Uvoz završen',
            'description'   => 'Vaši podaci <strong>:type</strong> u liniji <strong>:count</strong> su uspješno uvezeni.',

        ],

        'new_apps' => [

            'title'         => 'Nova aplikacija',
            'description'   => 'Aplikacija <strong>:name</strong> je nestala. Možete <a href=":url">kliknuti ovdje</a> da vidite detalje.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nova faktura',
            'description'   => '<strong>:invoice_number</strong> faktura je kreirana. Možete <a href=":invoice_portal_link">kliknuti ovdje</a> da vidite detalje i nastavite s plaćanjem.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Prekoračen je period plaćanja',
            'description'   => '<strong>:invoice_number</strong> faktura je dospjela <strong>:invoice_due_date</strong>. Možete <a href=":invoice_portal_link">kliknuti ovdje</a> da vidite detalje i nastavite s plaćanjem.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Prekoračen je period plaćanja',
            'description'   => '<strong>:invoice_number</strong> faktura je dospjela <strong>:invoice_due_date</strong>. Možete <a href=":invoice_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nova ponavljajuća faktura',
            'description'   => '<strong>:invoice_number</strong> faktura se kreira na osnovu vašeg kruga koji se ponavlja. Možete <a href=":invoice_portal_link">kliknuti ovdje</a> da vidite detalje i nastavite s plaćanjem.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nova ponavljajuća faktura',
            'description'   => '<strong>:invoice_number</strong> faktura je kreirana na osnovu ponavljajućeg kruga <strong>:customer_name</strong>. Možete <a href=":invoice_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktura pregledana',
            'description'   => '<strong>:customer_name</strong> je pregledao fakturu <strong>:invoice_number</strong>. Možete <a href=":invoice_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Uplata primljena',
            'description'   => 'Hvala vam na uplati za fakturu <strong>:invoice_number</strong>. Možete <a href=":invoice_portal_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Uplata primljena',
            'description'   => 'Hvala vam na uplati za fakturu <strong>:invoice_number</strong>. Možete <a href=":invoice_portal_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Uplata primljena',
            'description'   => ':customer_name je evidentirano plaćanje za fakturu <strong>:invoice_number</strong>. Možete <a href=":invoice_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Zakasnili račun',
            'description'   => '<strong>:bill_number</strong> Račun je dospio <strong>:bill_due_date</strong>. Možete <a href=":bill_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Ponavljajući račun',
            'description'   => 'Račun <strong>:bill_number</strong> je kreiran na osnovu ponavljajućeg kruga <strong>:vendor_name</strong>. Možete <a href=":bill_admin_link">kliknuti ovdje</a> da vidite detalje.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type je pročitano obavještenje',
        'mark_read_all'         => ':type je pročitana sva obavještenja!',

    ],
];
