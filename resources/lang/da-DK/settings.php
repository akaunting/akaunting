<?php

return [

    'company' => [
        'description'                => 'Ændre navn, email, adresse, CVR-nummer mv.',
        'name'                       => 'Navn',
        'email'                      => 'E-mail',
        'phone'                      => 'Telefon',
        'address'                    => 'Adresse',
        'edit_your_business_address' => 'Rediger din adresse',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Indstil regnskabsår, tidszone, datoformat og lokalindstillinger',
        'financial_start'   => 'Regnskabsårets Start',
        'timezone'          => 'Tidszone',
        'financial_denote' => [
            'title'         => 'Regnskabets tilhørsår',
            'begins'        => 'Det år, hvor det begynder',
            'ends'          => 'Det år, hvor det ender',
        ],
        'date' => [
            'format'        => 'Datoformat',
            'separator'     => 'Datoseparator',
            'dash'          => 'Bindestreg (-)',
            'dot'           => 'Punktum (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Skråstreg (/)',
            'space'         => 'Mellemrum ( )',
        ],
        'percent' => [
            'title'         => 'Procent (%) Position',
            'before'        => 'Før nummer',
            'after'         => 'Efter nummer',
        ],
        'discount_location' => [
            'name'          => 'Rabat lokation',
            'item'          => 'På linie',
            'total'         => 'Ved total',
            'both'          => 'Både linje og total',
        ],
    ],

    'invoice' => [
        'description'       => 'Tilpas fakturapræfiks, nummer, vilkår, sidefod etc',
        'prefix'            => 'Nummerpræfiks',
        'digit'             => 'Antal cifre',
        'next'              => 'Næste nummer',
        'logo'              => 'Logo',
        'custom'            => 'Tilpasset',
        'item_name'         => 'Varenavn',
        'item'              => 'Varer',
        'product'           => 'Produkter',
        'service'           => 'Services',
        'price_name'        => 'Pris navn',
        'price'             => 'Pris',
        'rate'              => 'Sats',
        'quantity_name'     => 'Mængde navn',
        'quantity'          => 'Antal',
        'payment_terms'     => 'Betalingsbetingelser',
        'title'             => 'Titel',
        'subheading'        => 'Undertitel',
        'due_receipt'       => 'Forfalder ved modtagelse',
        'due_days'          => 'Forfalder efter :days dage',
        'choose_template'   => 'Vælg faktura skabelon',
        'default'           => 'Standard',
        'classic'           => 'Klassisk',
        'modern'            => 'Moderne',
        'hide'              => [
            'item_name'         => 'Skjul navn',
            'item_description'  => 'Skjul beskrivelse',
            'quantity'          => 'Skjul antal',
            'price'             => 'Skjul pris',
            'amount'            => 'Skjul beløb',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Vælg overførselsskabelon',
        'second'            => 'Anden',
        'third'             => 'Tredje',
    ],

    'default' => [
        'description'       => 'Standard konto, valuta og sprog',
        'list_limit'        => 'Poster pr side',
        'use_gravatar'      => 'Brug standard-ikoner (Gravatar)',
        'income_category'   => 'Indtægtskategori',
        'expense_category'  => 'Udgiftskategori',
    ],

    'email' => [
        'description'       => 'Ændre sende protokol og e-mail skabeloner',
        'protocol'          => 'Protokol',
        'php'               => 'PHP mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP Brugernavn',
            'password'      => 'SMTP adgangskode',
            'encryption'    => 'SMTP sikkerhed',
            'none'          => 'Ingen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail sti',
        'log'               => 'Log E-mails',

        'templates' => [
            'subject'                   => 'Emne',
            'body'                      => 'Indhold',
            'tags'                      => '<strong>Tilgængelige Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'Skabelon til ny faktura (sendt til kunder)',
            'invoice_remind_customer'   => 'Fakturapåmindelses-skabelon (sendt til kunder)',
            'invoice_remind_admin'      => 'Fakturapåmindelses-skabelon (sendt til administrator)',
            'invoice_recur_customer'    => 'Skabelon for tilbagevendende faktura (sendt til kunde)',
            'invoice_recur_admin'       => 'Skabelon for tilbagevendende faktura (sendt til administrator)',
            'invoice_payment_customer'  => 'Skabelon for modtaget betaling (sendt til kunder)',
            'invoice_payment_admin'     => 'Skabelon for modtaget betaling (sendt til administrator)',
            'bill_remind_admin'         => 'Skabelon for regningspåmindelse (sendt til administrator)',
            'bill_recur_admin'          => 'Skabelon for tilbagevendende regning (sendt til administrator)',
            'revenue_new_customer'      => 'Skabelon for modtaget betaling (sendt til kunder)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planlægning',
        'description'       => 'Automatiske påmindelser og gentagelser',
        'send_invoice'      => 'Send fakturapåmindelse',
        'invoice_days'      => 'Send efter forfaldsdato',
        'send_bill'         => 'Send regningspåmindelse',
        'bill_days'         => 'Send før forfaldsdato',
        'cron_command'      => 'Cron kommando',
        'schedule_time'     => 'Afsendingstid',
    ],

    'categories' => [
        'description'       => 'Ubegrænsede kategorier for indtægter, udgifter og varer',
    ],

    'currencies' => [
        'description'       => 'Opret og administrer valutaer, og indstil renter',
    ],

    'taxes' => [
        'description'       => 'Faste, normale, inklusive og sammensatte skattesatser',
    ],

];
