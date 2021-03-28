<?php

return [

    'company' => [
        'description'       => 'Bedrijfsnaam, e-mail, adres, belastingnummer etc. wijzigen',
        'name'              => 'Naam',
        'email'             => 'E-mail',
        'phone'             => 'Telefoonnummer',
        'address'           => 'Adres',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Stel fiscaal jaar, tijdzone, datumnotatie en meer lokale instellingen in.',
        'financial_start'   => 'Start financieel boekjaar',
        'timezone'          => 'Tijdzone',
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
        'item_name'         => 'Item Naam',
        'item'              => 'Artikel|Artikelen',
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
    ],

    'default' => [
        'description'       => 'Standaard rekening, valuta, taal van uw bedrijf',
        'list_limit'        => 'Resultaten per pagina',
        'use_gravatar'      => 'Gebruik Gravatar',
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
            'invoice_remind_admin'      => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_recur_customer'    => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_recur_admin'       => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_payment_customer'  => 'Betaling ontvangen sjabloom (verzonden naar de klant)',
            'invoice_payment_admin'     => 'Betaling ontvangen sjabloon (verzonden naar admin)',
            'bill_remind_admin'         => 'Betalingsherinnering Template (verzonden naar admin) ',
            'bill_recur_admin'          => 'Abonnementsfactuur sjabloon (verzonden naar admin)',
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
