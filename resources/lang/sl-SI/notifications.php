<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Dobrodošli!',
    'salutation'          => 'S spoštovanjem,<br> : company_name',
    'subcopy'             => 'Če imate težave s klikom na gumb ": besedilo", kopirajte in prilepite spodnji URL v spletni brskalnik: [: url](:url)',
    'mark_read'           => 'Označi kot prebrano',
    'mark_read_all'       => 'Označi vse kot prebrano',
    'empty'               => 'Super, nimate obvestil!',
    'new_apps'            => 'Nova aplikacija|Novih aplikacij',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Posodobitev ni uspela na :domain',
            'description'   => 'Posodobitev :alias iz :current_version v :new_version ni uspela v koraku <strong>:step</strong> z naslednjim sporočilom: :error_message',

        ],

        'slack' => [

            'description'   => 'Posodobitev ni uspela na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Uvoz končan',
            'description'   => 'Uvoz je končan in zapisi so na voljo na nadzorni plošči.',

        ],

        'failed' => [

            'title'         => 'Uvoz ni uspel',
            'description'   => 'Datoteke ni mogoče uvoziti zaradi težave:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Izvoz je pripravljen',
            'description'   => 'Izvozna datoteka je pripravljena za prenos s povezave:',

        ],

        'failed' => [

            'title'         => 'Izvoz ni uspel',
            'description'   => 'Izvozne datoteke ni bilo mogoče ustvariti zaradi težave:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Neveljavna :type e-pošta',
            'description'   => 'E-poštni naslov :email je bil prijavljen kot neveljaven in oseba je bila onemogočena. Preverite naslednje sporočilo o napaki in popravite e-poštni naslov:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Izvoz je pripravljen',
            'description'   => 'Vaša izvozna datoteka <strong>:type</strong> je pripravljena za <a href=":url" target="_blank"><strong>prenos</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Izvoz ni uspel',
            'description'   => 'Izvozne datoteke ni mogoče ustvariti zaradi več težav. Preverite svojo e-pošto za podrobnosti.',

        ],

        'import_completed' => [

            'title'         => 'Uvoz končan',
            'description'   => 'Podatki <strong>:count</strong> v vrstici <strong>:type</strong> so uspešno uvoženi.',

        ],

        'import_failed' => [

            'title'         => 'Uvoz ni uspel',
            'description'   => 'Datoteke ni mogoče uvoziti zaradi več težav. Preverite svojo e-pošto za podrobnosti.',

        ],

        'new_apps' => [

            'title'         => 'Nova aplikacija',
            'description'   => 'Aplikacija <strong>:name</strong> ni več na voljo. Za ogled podrobnosti lahko <a href=":url">kliknete tukaj</a>.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nov račun',
            'description'   => '<strong>:invoice_number</strong> račun je ustvarjen. Za ogled podrobnosti in nadaljevanje plačila lahko <a href=":invoice_portal_link">kliknete tukaj</a>.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Račun je zapadel',
            'description'   => '<strong>:invoice_number</strong> račun je zapadel <strong>:invoice_due_date</strong>. Za ogled podrobnosti in nadaljevanje plačila lahko <a href=":invoice_portal_link">kliknete tukaj</a>.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Račun je zapadel',
            'description'   => '<strong>:invoice_number</strong> račun je zapadel <strong>:invoice_due_date</strong>. Za ogled podrobnosti lahko <a href=":invoice_admin_link">kliknete tukaj</a>.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nov ponavljajoči se račun',
            'description'   => '<strong>:invoice_number</strong> račun je ustvarjen na podlagi vašega ponavljajočega se kroga. Za ogled podrobnosti in nadaljevanje plačila lahko <a href=":invoice_portal_link">kliknete tukaj</a>.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nov ponavljajoči se račun',
            'description'   => '<strong>:invoice_number</strong> račun je ustvarjen na podlagi ponavljajočega se kroga <strong>:customer_name</strong>. Za ogled podrobnosti lahko <a href=":invoice_admin_link">kliknete tukaj</a>.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Račun ogledan',
            'description'   => '<strong>:customer_name</strong> si je ogledal račun <strong>:invoice_number</strong>. Za ogled podrobnosti lahko <a href=":invoice_admin_link">kliknete tukaj</a>.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Plačilo prejeto',
            'description'   => 'Zahvaljujemo se vam za plačilo za račun <strong>:invoice_number</strong>. Za ogled podrobnosti lahko <a href=":invoice_portal_link">kliknete tukaj</a>.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Plačilo prejeto',
            'description'   => 'Zahvaljujemo se vam za plačilo za račun <strong>:invoice_number</strong>. Za ogled podrobnosti lahko <a href=":invoice_portal_link">kliknete tukaj</a>.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Plačilo prejeto',
            'description'   => ':customer_name je zabeležil plačilo za račun <strong>:invoice_number</strong>. Za ogled podrobnosti lahko <a href=":invoice_admin_link">kliknete tukaj</a>.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Račun je zapadel',
            'description'   => 'Račun <strong>:bill_number</strong> je zapadel <strong>:bill_due_date</strong>. Za ogled podrobnosti lahko <a href=":bill_admin_link">kliknete tukaj</a>.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nov ponavljajoči se račun',
            'description'   => 'Račun <strong>:bill_number</strong> je ustvarjen na podlagi ponavljajočega se kroga <strong>:vendor_name</strong>. Za ogled podrobnosti lahko <a href=":bill_admin_link">kliknete tukaj</a>.',

        ],

        'invalid_email' => [

            'title'         => 'Neveljavna :type e-pošta',
            'description'   => 'E-poštni naslov <strong>:email</strong> je bil prijavljen kot neveljaven in oseba je bila onemogočena. Preverite in popravite e-poštni naslov.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type je prebral to obvestilo!',
        'mark_read_all'         => ':type je prebral vsa obvestila!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Konfiguracija ikone Firefox',
            'description'  => '<span class="font-medium">Če se vaše ikone ne prikažejo;</span> <br /> <span class="font-medium">Dovolite stranem, da izberejo lastne pisave namesto vaših zgornjih izbir</span> <br /><br /> <span class="font-bold"> Nastavitve (Nastavitve) > Pisave > Napredno </span>',

        ],

    ],

];
