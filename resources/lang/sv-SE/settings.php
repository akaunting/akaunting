<?php

return [

    'company' => [
        'description'       => 'Ändra företagsnamn, e-postadress, adress, skattenummer etc',
        'name'              => 'Namn',
        'email'             => 'E-post',
        'phone'             => 'Telefon',
        'address'           => 'Adress',
        'logo'              => 'Logotyp',
    ],

    'localisation' => [
        'description'       => 'Ställ in räkenskapsår, tidszon, datumformat och andra lokala inställningar',
        'financial_start'   => 'Räkenskapsår',
        'timezone'          => 'Tidszon',
        'date' => [
            'format'        => 'Datumformat',
            'separator'     => 'Datumavgränsare',
            'dash'          => 'Bindestreck (-)',
            'dot'           => 'Punkt (.)',
            'comma'         => 'Kommatecken ()',
            'slash'         => 'Snedstreck (/)',
            'space'         => 'Mellanslag ( )',
        ],
        'percent' => [
            'title'         => 'Procent (%) Ställning',
            'before'        => 'Innan nummret',
            'after'         => 'Efter nummret',
        ],
    ],

    'invoice' => [
        'description'       => 'Anpassa prefix, nummer, villkor, sidfot osv',
        'prefix'            => 'Nummerprefix',
        'digit'             => 'Siffra',
        'next'              => 'Nästa nummer',
        'logo'              => 'Logotyp',
        'custom'            => 'Anpassad',
        'item_name'         => 'Artikelnamn',
        'item'              => 'Artiklar',
        'product'           => 'Produkter',
        'service'           => 'Tjänster',
        'price_name'        => 'Pris namn',
        'price'             => 'Pris',
        'rate'              => 'Kurs',
        'quantity_name'     => 'Antal namn',
        'quantity'          => 'Antal',
        'payment_terms'     => 'Betalningsvillkor',
        'title'             => 'Titel',
        'subheading'        => 'Underrubrik',
        'due_receipt'       => 'Förfaller vid mottagandet',
        'due_days'          => 'Förfaller inom :days dagar',
        'choose_template'   => 'Välj fakturamall',
        'default'           => 'Standard',
        'classic'           => 'Klassisk',
        'modern'            => 'Modern',
    ],

    'default' => [
        'description'       => 'Standardkonto, valuta, ditt företags språk',
        'list_limit'        => 'Poster Per sida',
        'use_gravatar'      => 'Använda Gravatar',
    ],

    'email' => [
        'description'       => 'Ändra sändningsprotokollet och e-postmallarna',
        'protocol'          => 'Protokoll',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Server',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP Användarnamn',
            'password'      => 'SMTP Lösenord',
            'encryption'    => 'SMTP Säkerhet',
            'none'          => 'Ingen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sökväg för Sendmail',
        'log'               => 'Logga e-post',

        'templates' => [
            'subject'                   => 'Ämne',
            'body'                      => 'Brödtext',
            'tags'                      => '<strong>Tillgängliga taggar:</strong> :tag_list',
            'invoice_new_customer'      => 'Ny fakturamall (skickas till kund)',
            'invoice_remind_customer'   => 'Fakturaminnesmall (skickad till kund)',
            'invoice_remind_admin'      => 'Fakturaminnesmall (skickad till kund)',
            'invoice_recur_customer'    => 'Återkommande fakturamall (skickad till kund)',
            'invoice_recur_admin'       => 'Återkommande fakturamall (skickas till Admin)',
            'invoice_payment_customer'  => '44/5000
Mottagning av betalning (skickad till kund)',
            'invoice_payment_admin'     => 'Mottagning av betalning (skickad till admin)',
            'bill_remind_admin'         => 'Faktura påminnelsemall (skickad till admin)',
            'bill_recur_admin'          => 'Räkning återkommande mall (skickad till admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Schemaläggning',
        'description'       => 'Automatiska påminnelser och kommando för återkommande',
        'send_invoice'      => 'Skicka faktura påminnelse',
        'invoice_days'      => 'Skicka efter förfallodagar',
        'send_bill'         => 'Skicka faktura påminnelse',
        'bill_days'         => 'Skicka innan förfallodatum',
        'cron_command'      => 'Cron Kommando',
        'schedule_time'     => 'Tid att köra',
    ],

    'categories' => [
        'description'       => 'Obegränsade kategorier för inkomst, kostnad och artikel',
    ],

    'currencies' => [
        'description'       => 'Skapa och hantera valutor och ställ in dina kurser',
    ],

    'taxes' => [
        'description'       => 'Fasta, normala, inklusive och sammansatta skattesatser',
    ],

];
