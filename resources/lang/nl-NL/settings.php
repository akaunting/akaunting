<?php

return [

    'company' => [
        'description'                => 'Bedrijfsnaam, e-mail, adres, belastingnummer etc. wijzigen',
        'name'                       => 'Naam',
        'email'                      => 'E-mail',
        'phone'                      => 'Telefoonnummer',
        'address'                    => 'Adres',
        'edit_your_business_address' => 'Bewerk uw bedrijfsadres',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Stel fiscaal jaar, tijdzone, datumnotatie en meer lokale instellingen in.',
        'financial_start'   => 'Start financieel boekjaar',
        'timezone'          => 'Tijdzone',
        'financial_denote' => [
            'title'         => 'Fiscale jaarweergave',
            'begins'        => 'In het jaar waarin het financieel boekjaar begint',
            'ends'          => 'In het jaar waarin het financieel boekjaar eindigt',
        ],
        'date' => [
            'format'        => 'Datum formaat',
            'separator'     => 'Datumscheidingsteken',
            'dash'          => 'Streepje (-)',
            'dot'           => 'Punt (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spatie ( ) ',
        ],
        'percent' => [
            'title'         => 'Procent (%) Positie',
            'before'        => 'Voor aantal',
            'after'         => 'Na aantal',
        ],
        'discount_location' => [
            'name'          => 'Korting locatie',
            'item'          => 'Op regel',
            'total'         => 'In totaal',
            'both'          => 'Zowel lijn als totaal',
        ],
    ],

    'invoice' => [
        'description'       => 'Factuurprefix, nummer, betaaltermijn, voettekst etc. aanpassen',
        'prefix'            => 'Nummer voorvoegsel',
        'digit'             => 'Aantal cijfers',
        'next'              => 'Volgende nummer',
        'logo'              => 'Logo',
        'custom'            => 'Aangepast',
        'item_name'         => 'Artikel Naam',
        'item'              => 'Artikelen',
        'product'           => 'Producten',
        'service'           => 'Diensten',
        'price_name'        => 'Prijs label',
        'price'             => 'Prijs',
        'rate'              => 'Tarief',
        'quantity_name'     => 'Hoeveelheid label',
        'quantity'          => 'Aantal',
        'payment_terms'     => 'Betalingsvoorwaarden',
        'title'             => 'Titel',
        'subheading'        => 'Subkop',
        'due_receipt'       => 'Vervalt bij ontvangst',
        'due_days'          => 'Vervalt binnen :days',
        'choose_template'   => 'Factuursjabloon kiezen',
        'default'           => 'Standaard',
        'classic'           => 'Klassiek',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Naam artikel/dienst verbergen',
            'item_description'  => 'Omschrijving artikel/dienst verbergen',
            'quantity'          => 'Hoeveelheid verbergen',
            'price'             => 'Prijs verbergen',
            'amount'            => 'Bedrag verbergen',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Kies het overdrachtssjabloon',
        'second'            => 'Tweede',
        'third'             => 'Derde',
    ],

    'default' => [
        'description'       => 'Standaard rekening, valuta, taal van uw bedrijf',
        'list_limit'        => 'Resultaten per pagina',
        'use_gravatar'      => 'Gebruik Gravatar',
        'income_category'   => 'Inkomenscategorie',
        'expense_category'  => 'Onkostencategorie',
    ],

    'email' => [
        'description'       => 'Wijzig de verzendprotocol en e-mailsjablonen',
        'protocol'          => 'Protocol',
        'php'               => 'PHP mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP host',
            'port'          => 'SMTP-poort',
            'username'      => 'SMTP gebruikersnaam',
            'password'      => 'SMTP wachtwoord',
            'encryption'    => 'SMTP beveiliging',
            'none'          => 'Geen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail pad',
        'log'               => 'E-mail logs',

        'templates' => [
            'subject'                   => 'Onderwerp',
            'body'                      => 'Inhoud',
            'tags'                      => '<strong>Beschikbare tags:</strong> :tag_list',
            'invoice_new_customer'      => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_remind_customer'   => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_remind_admin'      => 'Factuurherinneringssjabloon (verzonden naar beheerder)',
            'invoice_recur_customer'    => 'Terugkerende factuursjabloon (verzonden naar klant)',
            'invoice_recur_admin'       => 'Terugkerende factuursjabloon (verzonden naar admin)',
            'invoice_payment_customer'  => 'Betaling ontvangen sjabloon (verzonden naar de klant)',
            'invoice_payment_admin'     => 'Betaling ontvangen sjabloon (verzonden naar admin)',
            'bill_remind_admin'         => 'Sjabloon voor onkostenfactuur (verzonden naar beheerder)',
            'bill_recur_admin'          => 'Sjabloon voor terugkerende onkostenfactuur (verzonden naar beheerder)',
            'revenue_new_customer'      => 'Inkomstenbevestigingssjabloon (verzonden naar klant)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planning',
        'description'       => 'Automatische herinneringen en abonnementsfacturen',
        'send_invoice'      => 'Factuur herinnering sturen',
        'invoice_days'      => 'Aantal dagen na vervaldatum sturen',
        'send_bill'         => 'Factuur herinnering sturen',
        'bill_days'         => 'Aantal dagen voor vervaldatum sturen',
        'cron_command'      => 'Cron opdracht',
        'schedule_time'     => 'Uren duurtijd',
    ],

    'categories' => [
        'description'       => 'Onbeperkt aantal categorieën voor inkomen, uitgaven en producten',
    ],

    'currencies' => [
        'description'       => 'Maak en beheer valuta en stel de koersen in',
    ],

    'taxes' => [
        'description'       => 'Vaste normaal, inclusief, en samengestelde belastingtarieven',
    ],

];
