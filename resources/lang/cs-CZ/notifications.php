<?php

return [

    'whoops'              => 'Jejda!',
    'hello'               => 'Ahoj!',
    'salutation'          => 'S pozdravem,<br> :company_name',
    'subcopy'             => 'Pokud vám nefunguje tlačítko ":text", zkopírujte a vložte adresu URL do prohlížeče: [:url](:url)',
    'mark_read'           => 'Označit jako přečtené',
    'mark_read_all'       => 'Označit vše jako přečtené',
    'empty'               => 'Bezva, žádná oznámení!',
    'new_apps'            => ':app je k dispozici. <a href=":url">Pojďme se podívat</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Aktualizace se nezdařila na :domain',
            'description'   => 'Aktualizace :alias z :current_version na :new_version selhala v <strong>:step</strong> krok s následující zprávou: :error_message',

        ],

        'slack' => [

            'description'   => 'Aktualizace se nezdařila na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Import dokončen',
            'description'   => 'Import byl dokončen a záznamy jsou k dispozici ve vašem panelu.',

        ],

        'failed' => [

            'title'         => 'Import se nezdařil',
            'description'   => 'Není možné importovat soubor z důvodu následujících důvodů:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Export je připraven',
            'description'   => 'Exportovaný soubor je připraven ke stažení z následujícího odkazu:',

        ],

        'failed' => [

            'title'         => 'Export selhal',
            'description'   => 'Není možné vytvořit exportovaný soubor z důvodu následujícího důvodu:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Neplatný :type e-mail',
            'description'   => ':email byla nahlášena jako neplatný a osoba byla deaktivována. Zkontrolujte prosím následující chybovou zprávu a opravte e-mailovou adresu:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Export je připraven',
            'description'   => 'Váš <strong>:type</strong> exportovaný soubor je připraven na <a href=":url" target="_blank"><strong>stažení</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Export selhal',
            'description'   => 'Není možné vytvořit exportní soubor z důvodu několika problémů. Podívejte se na váš e-mail pro detaily.',

        ],

        'import_completed' => [

            'title'         => 'Import dokončen',
            'description'   => 'Vaše <strong>:type</strong> vložená data <strong>:count</strong> jsou úspěšně importována.',

        ],

        'import_failed' => [

            'title'         => 'Import se nezdařil',
            'description'   => 'Není možné importovat soubor z důvodu několika problémů. Podívejte se na váš e-mail pro detaily.',

        ],

        'new_apps' => [

            'title'         => 'Nová aplikace',
            'description'   => '<strong>:name</strong> aplikace je zastaralá. Můžete <a href=":url">kliknout zde</a> a zobrazit podrobnosti.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nová faktura',
            'description'   => '<strong>:invoice_number</strong> faktura je vytvořena. Můžete <a href=":invoice_portal_link">kliknout zde</a> a zobrazit podrobnosti a pokračovat v platbě.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Faktura po splatnosti',
            'description'   => '<strong>:invoice_number</strong> faktura byla splatná <strong>:invoice_due_date</strong>. Můžete <a href=":invoice_portal_link">kliknout zde</a> a zobrazit podrobnosti a pokračovat v platbě.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Faktura po splatnosti',
            'description'   => '<strong>:invoice_number</strong> faktura byla splatná <strong>:invoice_due_date</strong>. Můžete <a href=":invoice_admin_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nová opakující se faktura',
            'description'   => '<strong>:invoice_number</strong> faktura je vytvořena na základě vašeho opakujícího plánu. Můžete <a href=":invoice_portal_link">kliknout zde</a> pro zobrazení podrobností a pokračovat v platbě.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nová opakující se faktura',
            'description'   => '<strong>:invoice_number</strong> faktura je vytvořena na základě opakovacího plánu <strong>:customer_name</strong> . Můžete <a href=":invoice_admin_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktura zobrazena',
            'description'   => '<strong>:customer_name</strong> si prohlédl fakturu <strong>:invoice_number</strong> . Můžete <a href=":invoice_admin_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Platba přijata',
            'description'   => 'Děkujeme vám za platbu faktury <strong>:invoice_number</strong> . Můžete <a href=":invoice_portal_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Platba přijata',
            'description'   => 'Děkujeme vám za platbu faktury <strong>:invoice_number</strong> . Můžete <a href=":invoice_portal_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Platba přijata',
            'description'   => ':customer_name zaznamenal platbu za fakturu <strong>:invoice_number</strong> . Můžete <a href=":invoice_admin_link">kliknout zde</a> pro zobrazení podrobností.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Faktura po splatnosti',
            'description'   => '<strong>:bill_number</strong> účet byl uhrazen <strong>:bill_due_date</strong>. Můžete <a href=":bill_admin_link">kliknout zde</a> a zobrazit podrobnosti.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nový opakující se účet',
            'description'   => '<strong>:bill_number</strong> účet je vytvořen na základě opakování <strong>:vendor_name</strong> . Můžete <a href=":bill_admin_link">kliknout zde</a> a zobrazit podrobnosti.',

        ],

        'invalid_email' => [

            'title'         => 'Neplatný :type e-mail',
            'description'   => 'E-mailová adresa <strong>:email</strong> byla nahlášena jako neplatná a osoba byla deaktivována. Prosím zkontrolujte a opravte e-mailovou adresu.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type přečte toto oznámení!',
        'mark_read_all'         => ':type je načtení všech oznámení!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Nastavení Firefox ikony',
            'description'  => '<span class="font-medium">Pokud se vaše ikony nezobrazují prosím;</span> <br /> <span class="font-medium">Prosím povolte stránkám vybrat si vlastní písma, místo vašeho výběru nad</span> <br /><br /> <span class="font-bold"> Nastavení (Nastavení) > Písma > Pokročilé </span>',

        ],

    ],

];
