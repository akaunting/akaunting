<?php

return [

    'company' => [
        'description'                => 'Schimbă numele companiei, e-mail, adresă, cod fiscal etc',
        'name'                       => 'Nume',
        'email'                      => 'Email',
        'phone'                      => 'Telefon',
        'address'                    => 'Adresă',
        'edit_your_business_address' => 'Editați adresa companiei',
        'logo'                       => 'Siglă',
    ],

    'localisation' => [
        'description'       => 'Setați anul fiscal, zona orară, formatul datei și alte localizări',
        'financial_start'   => 'Început an fiscal',
        'timezone'          => 'Fus orar',
        'financial_denote' => [
            'title'         => 'Început an fiscal',
            'begins'        => 'Până în anul în care începe',
            'ends'          => 'Până la anul în care se încheie',
        ],
        'date' => [
            'format'        => 'Format dată',
            'separator'     => 'Separator dată',
            'dash'          => 'Cratimă (-)',
            'dot'           => 'Punct (.)',
            'comma'         => 'Virgulă (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spaţiu ( )',
        ],
        'percent' => [
            'title'         => 'Procent (%) Pozitie',
            'before'        => 'Înainte de număr',
            'after'         => 'După număr',
        ],
        'discount_location' => [
            'name'          => 'Locație reducere',
            'item'          => 'La rândul',
            'total'         => 'La total',
            'both'          => 'Atât pe linie cât și la total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizează seria facturii, numărul, termenii, subsolul etc.',
        'prefix'            => 'Prefix',
        'digit'             => 'Zecimale numar',
        'next'              => 'Următorul număr',
        'logo'              => 'Siglă',
        'custom'            => 'Personalizat',
        'item_name'         => 'Denumire articol',
        'item'              => 'Articole',
        'product'           => 'Produse',
        'service'           => 'Servicii',
        'price_name'        => 'Numele prețului',
        'price'             => 'Preț',
        'rate'              => 'Rată',
        'quantity_name'     => 'Numele cantității',
        'quantity'          => 'Cantitate',
        'payment_terms'     => 'Termeni de plată',
        'title'             => 'Titlu',
        'subheading'        => 'Subantet',
        'due_receipt'       => 'De achitat la primire',
        'due_days'          => 'Scadentă în :days',
        'choose_template'   => 'Alegeți șablonul facturii',
        'default'           => 'Implicit',
        'classic'           => 'Clasic',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Ascunde Numele Articolului',
            'item_description'  => 'Ascunde Descrierea Articolului',
            'quantity'          => 'Ascunde Cantitate',
            'price'             => 'Ascunde Preț',
            'amount'            => 'Ascunde Suma',
        ],
    ],

    'default' => [
        'description'       => 'Contul implicit, moneda, limba companiei dvs.',
        'list_limit'        => 'Înregistrări pe pagină',
        'use_gravatar'      => 'Foloseste Gravatar',
        'income_category'   => 'Venituri pe categorii',
        'expense_category'  => 'Cheltuieli pe categorii',
    ],

    'email' => [
        'description'       => 'Modifică protocolul de trimitere și șabloanele de e-mail',
        'protocol'          => 'Protocol',
        'php'               => 'Mail PHP',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Gazdă SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Utilizator SMTP',
            'password'      => 'Parolă SMTP',
            'encryption'    => 'Securitate SMTP',
            'none'          => 'Nici una',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Cale Sendmail',
        'log'               => 'Jurnal Email-uri',

        'templates' => [
            'subject'                   => 'Subiect',
            'body'                      => 'Conţinut:',
            'tags'                      => '<strong>Etichete disponibile:</strong> :tag_list',
            'invoice_new_customer'      => 'Șablon nou de factură (trimis către client)',
            'invoice_remind_customer'   => 'Șablon memento factură (trimis către client)',
            'invoice_remind_admin'      => 'Șablon memento factură (trimis administratorului)',
            'invoice_recur_customer'    => 'Șablon recurent factură (trimis către client)',
            'invoice_recur_admin'       => 'Șablon memento factură (trimis administratorului)',
            'invoice_payment_customer'  => 'Șablon plată primită (trimis către client)',
            'invoice_payment_admin'     => 'Șablon plată primită (trimis către administrator)',
            'bill_remind_admin'         => 'Șablon memento factură (trimis administratorului)',
            'bill_recur_admin'          => 'Șablon memento factură (trimis administratorului)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planificare',
        'description'       => 'Memento-uri automate și comanda pentru recurență',
        'send_invoice'      => 'Trimite memento pentru factura',
        'invoice_days'      => 'Trimite dupa Zilele Cuvenite',
        'send_bill'         => 'Trimite memento pentru factura',
        'bill_days'         => 'Trimite Inainte de Zilele Cuvenite',
        'cron_command'      => 'Comanda Cron',
        'schedule_time'     => 'Ora la care ruleaza',
    ],

    'categories' => [
        'description'       => 'Categorii nelimitate de venituri, cheltuieli și articole',
    ],

    'currencies' => [
        'description'       => 'Creați și gestionați valutele și stabiliți ratele lor de schimb',
    ],

    'taxes' => [
        'description'       => 'Cote de impozitare fixe, normale, totale și compuse',
    ],

];
