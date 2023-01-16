<?php

return [

    'company' => [
        'description'                   => 'Ändra företagsnamn, e-postadress, adress, skattenummer etc',
        'search_keywords'               => 'företag, namn, e-post, telefon, adress, land, momsregistreringsnummer, logotyp, stad, stad, stat, provins, postnummer',
        'name'                          => 'Namn',
        'email'                         => 'E-post',
        'phone'                         => 'Telefon',
        'address'                       => 'Adress',
        'edit_your_business_address'    => 'Redigera din företagsadress',
        'logo'                          => 'Logotyp',

        'form_description' => [
            'general'                   => 'Denna information är synlig i de poster du skapar.',
            'address'                   => 'Adressen kommer att användas i fakturor, räkningar och andra poster som du utfärdar.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Ställ in räkenskapsår, tidszon, datumformat och andra lokala inställningar',
        'search_keywords'               => 'räkenskap, år, start, beteckning, tid, zon, datum, format, separator, rabatt, procent',
        'financial_start'               => 'Räkenskapsår',
        'timezone'                      => 'Tidszon',
        'financial_denote' => [
            'title'                     => 'Räkenskapsårets Beteckning',
            'begins'                    => 'Efter startår',
            'ends'                      => 'Efter slutår',
        ],
        'preferred_date'                => 'Föredraget Datum',
        'date' => [
            'format'                    => 'Datumformat',
            'separator'                 => 'Datumavgränsare',
            'dash'                      => 'Bindestreck (-)',
            'dot'                       => 'Punkt (.)',
            'comma'                     => 'Kommatecken ()',
            'slash'                     => 'Snedstreck (/)',
            'space'                     => 'Mellanslag ( )',
        ],
        'percent' => [
            'title'                     => 'Procent (%) Ställning',
            'before'                    => 'Innan nummret',
            'after'                     => 'Efter nummret',
        ],
        'discount_location' => [
            'name'                      => 'Rabattplats',
            'item'                      => 'På rad',
            'total'                     => 'Totalt',
            'both'                      => 'Både rad och total',
        ],

        'form_description' => [
            'fiscal'                    => 'Ange det räkenskapsår som ditt företag använder för beskattning och rapportering.',
            'date'                      => 'Välj det datumformat som du vill använda för gränssnittet.',
            'other'                     => 'Välj var procenttecknet visas för moms. Du kan aktivera rabatter på rader och totalen för fakturor och räkningar.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Anpassa prefix, nummer, villkor, sidfot osv',
        'search_keywords'               => 'anpassa, faktura, nummer, prefix, siffra, nästa, logotyp, namn, pris, antal, mall, titel, underrubrik, sidfot, anteckning, dölja, förfaller, färg, betalning, villkor, kolumn',
        'prefix'                        => 'Nummerprefix',
        'digit'                         => 'Siffra',
        'next'                          => 'Nästa nummer',
        'logo'                          => 'Logotyp',
        'custom'                        => 'Anpassad',
        'item_name'                     => 'Artikelnamn',
        'item'                          => 'Artiklar',
        'product'                       => 'Produkter',
        'service'                       => 'Tjänster',
        'price_name'                    => 'Pris namn',
        'price'                         => 'Pris',
        'rate'                          => 'Kurs',
        'quantity_name'                 => 'Antal namn',
        'quantity'                      => 'Antal',
        'payment_terms'                 => 'Betalningsvillkor',
        'title'                         => 'Titel',
        'subheading'                    => 'Underrubrik',
        'due_receipt'                   => 'Förfaller vid mottagandet',
        'due_days'                      => 'Förfaller inom :days dagar',
        'choose_template'               => 'Välj fakturamall',
        'default'                       => 'Standard',
        'classic'                       => 'Klassisk',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Dölj objektnamn',
            'item_description'          => 'Dölj objektbeskrivning',
            'quantity'                  => 'Dölj Kvantitet',
            'price'                     => 'Dölj pris',
            'amount'                    => 'Dölj belopp',
        ],
        'column'                        => 'Kolumn|Kolumner',

        'form_description' => [
            'general'                   => 'Ange standardformatering för dina fakturanummer och betalningsvillkor.',
            'template'                  => 'Välj en av mallarna nedan för dina fakturor.',
            'default'                   => 'När du väljer standardinställningar för fakturor kommer titlar, underrubriker, anteckningar och sidfot fyllas i automatiskt. Så du slipper redigera fakturor varje gång för de ska se mer professionella ut.',
            'column'                    => 'Anpassa namnen på fakturans kolumner. Om du vill dölja artikelbeskrivningar och belopp i rader, kan du ändra det här.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Välj överföringsmall',
        'second'                        => 'Andra',
        'third'                         => 'Tredje',
    ],

    'default' => [
        'description'                   => 'Standardkonto, valuta, ditt företags språk',
        'search_keywords'               => 'konto, valuta, språk, skatt, betalning, metod, sidnumrering',
        'list_limit'                    => 'Poster Per sida',
        'use_gravatar'                  => 'Använda Gravatar',
        'income_category'               => 'Kategori för inkomst',
        'expense_category'              => 'Kategori för utlägg',

        'form_description' => [
            'general'                   => 'Välj standardkonto, skatt och betalningsmetod för att snabbt skapa poster. Övervakningspanel och rapporter visas i standardvalutan.',
            'category'                  => 'Välj standardkategorier för att förenkla skapandet av poster.',
            'other'                     => 'Anpassa standardinställningarna för företagets språk och hur sidnumrering fungerar. ',
        ],
    ],

    'email' => [
        'description'                   => 'Ändra sändningsprotokollet och e-postmallarna',
        'search_keywords'               => 'e-post, skicka, protokoll, smtp, värd, lösenord',
        'protocol'                      => 'Protokoll',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Server',
            'port'                      => 'SMTP Port',
            'username'                  => 'SMTP Användarnamn',
            'password'                  => 'SMTP Lösenord',
            'encryption'                => 'SMTP Säkerhet',
            'none'                      => 'Ingen',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sökväg för Sendmail',
        'log'                           => 'Logga e-post',
        'email_service'                 => 'E-Post Service',
        'email_templates'               => 'E-postmallar',

        'form_description' => [
            'general'                   => 'Skicka regelbundna e-postmeddelanden till ditt team och kontakter. Du kan ställa in protokoll- och SMTP-inställningar.',
        ],

        'templates' => [
            'description'               => 'Ändra e-postmallarna',
            'search_keywords'           => 'e-post, mall, ämne, brödtext, tagg',
            'subject'                   => 'Ämne',
            'body'                      => 'Brödtext',
            'tags'                      => '<strong>Tillgängliga taggar:</strong> :tag_list',
            'invoice_new_customer'      => 'Ny fakturamall (skickas till kund)',
            'invoice_remind_customer'   => 'Fakturaminnesmall (skickad till kund)',
            'invoice_remind_admin'      => 'Fakturaminnesmall (skickad till kund)',
            'invoice_recur_customer'    => 'Återkommande fakturamall (skickad till kund)',
            'invoice_recur_admin'       => 'Återkommande fakturamall (skickas till Admin)',
            'invoice_view_admin'        => 'Faktura Sedd Mall (skickas till administratör)',
            'invoice_payment_customer'  => '44/5000
Mottagning av betalning (skickad till kund)',
            'invoice_payment_admin'     => 'Mottagning av betalning (skickad till admin)',
            'bill_remind_admin'         => 'Faktura påminnelsemall (skickad till admin)',
            'bill_recur_admin'          => 'Räkning återkommande mall (skickad till admin)',
            'payment_received_customer' => 'Betalningskvittomall (skickas till kund)',
            'payment_made_vendor'       => 'Betalningsbekräftelsemall (skickas till leverantör)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Schemaläggning',
        'description'                   => 'Automatiska påminnelser och kommando för återkommande',
        'search_keywords'               => 'automatisk, påminnelse, återkommande, cron, kommando',
        'send_invoice'                  => 'Skicka faktura påminnelse',
        'invoice_days'                  => 'Skicka efter förfallodagar',
        'send_bill'                     => 'Skicka faktura påminnelse',
        'bill_days'                     => 'Skicka innan förfallodatum',
        'cron_command'                  => 'Cron Kommando',
        'command'                       => 'Kommando',
        'schedule_time'                 => 'Tid att köra',

        'form_description' => [
            'invoice'                   => 'Aktivera, inaktivera och/eller ställ in påminnelser för dina fakturor när de är försenade.',
            'bill'                      => 'Aktivera, inaktivera och/eller ställ in påminnelser för dina räkningar när de är försenade.',
            'cron'                      => 'Kopiera cron-kommandot som din server ska köra. Ställ in tiden då händelsen ska ske.',
        ]
    ],

    'categories' => [
        'description'                   => 'Obegränsade kategorier för inkomst, kostnad och artikel',
        'search_keywords'               => 'kategori, inkomst, kostnad, post',
    ],

    'currencies' => [
        'description'                   => 'Skapa och hantera valutor och ställ in dina kurser',
        'search_keywords'               => 'standard, valuta, valutor, kod, taxa, symbol, precision, position, decimal, tusental, markera, separator',
    ],

    'taxes' => [
        'description'                   => 'Fasta, normala, inklusive och sammansatta skattesatser',
        'search_keywords'               => 'skatt, taxa, typ, fast, inklusive, sammansatt, innehållande',
    ],

];
