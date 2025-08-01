<?php

return [

    'company' => [
        'description'                   => 'Bedrijfsnaam, e-mail, adres, belastingnummer etc. wijzigen',
        'search_keywords'               => 'bedrijf, naam, e-mail, telefoon, adres, land, belastingnummer, logo, stad, gemeente, staat, provincie, postcode',
        'name'                          => 'Naam',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefoonnummer',
        'address'                       => 'Adres',
        'edit_your_business_address'    => 'Bewerk uw bedrijfsadres',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Deze informatie is zichtbaar in de records die u aanmaakt.',
            'address'                   => 'Het adres wordt gebruikt in de facturen, rekeningen en andere documenten die u uitgeeft.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Stel fiscaal jaar, tijdzone, datumnotatie en meer lokale instellingen in.',
        'search_keywords'               => 'financieel, jaar, start, aanduiding, tijd, zone, datum, formaat, scheidingsteken, korting, procent',
        'financial_start'               => 'Start financieel boekjaar',
        'timezone'                      => 'Tijdzone',
        'financial_denote' => [
            'title'                     => 'Fiscale jaarweergave',
            'begins'                    => 'In het jaar waarin het financieel boekjaar begint',
            'ends'                      => 'In het jaar waarin het financieel boekjaar eindigt',
        ],
        'preferred_date'                => 'Voorkeursdatum',
        'date' => [
            'format'                    => 'Datum formaat',
            'separator'                 => 'Datumscheidingsteken',
            'dash'                      => 'Streepje (-)',
            'dot'                       => 'Punt (.)',
            'comma'                     => 'Komma (,)',
            'slash'                     => 'Slash (/)',
            'space'                     => 'Spatie ( ) ',
        ],
        'percent' => [
            'title'                     => 'Procent (%) Positie',
            'before'                    => 'Voor aantal',
            'after'                     => 'Na aantal',
        ],
        'discount_location' => [
            'name'                      => 'Korting locatie',
            'item'                      => 'Op regel',
            'total'                     => 'In totaal',
            'both'                      => 'Zowel lijn als totaal',
        ],

        'form_description' => [
            'fiscal'                    => 'Stel de periode van het boekjaar in die uw bedrijf gebruikt voor belastingheffing en rapportage.',
            'date'                      => 'Selecteer het datumformaat dat u overal in de interface wilt zien.',
            'other'                     => 'Selecteer de plaats waar het percentageteken wordt weergegeven voor belastingen. U kunt kortingen inschakelen op regelitems en op het totaal voor facturen en rekeningen.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Factuurprefix, nummer, betaaltermijn, voettekst etc. aanpassen',
        'search_keywords'               => 'aanpassen, factuur, aantal, voorvoegsel, cijfer, volgende, logo, naam, prijs, hoeveelheid, sjabloon, titel, ondertitel, voettekst, notitie, verbergen, verschuldigd, kleur, betaling, voorwaarden, kolom',
        'prefix'                        => 'Nummer voorvoegsel',
        'digit'                         => 'Aantal cijfers',
        'next'                          => 'Volgende nummer',
        'logo'                          => 'Logo',
        'custom'                        => 'Aangepast',
        'item_name'                     => 'Artikel label',
        'item'                          => 'Artikelen',
        'product'                       => 'Producten',
        'service'                       => 'Diensten',
        'price_name'                    => 'Prijs label',
        'price'                         => 'Prijs',
        'rate'                          => 'Tarief',
        'quantity_name'                 => 'Hoeveelheid label',
        'quantity'                      => 'Aantal',
        'payment_terms'                 => 'Betalingsvoorwaarden',
        'title'                         => 'Titel',
        'subheading'                    => 'Subkop',
        'due_receipt'                   => 'Vervalt bij ontvangst',
        'due_days'                      => 'Vervalt binnen :days',
        'due_custom'                    => 'Aangepaste dag(en)',
        'due_custom_day'                => 'na dag',
        'choose_template'               => 'Factuursjabloon kiezen',
        'default'                       => 'Standaard',
        'classic'                       => 'Klassiek',
        'modern'                        => 'Modern',
        'logo_size_width'               => 'Logo Breedte',
        'logo_size_height'              => 'Logo hoogte',
        'hide' => [
            'item_name'                 => 'Naam artikel/dienst verbergen',
            'item_description'          => 'Omschrijving artikel/dienst verbergen',
            'quantity'                  => 'Hoeveelheid verbergen',
            'price'                     => 'Prijs verbergen',
            'amount'                    => 'Bedrag verbergen',
        ],
        'column'                        => 'Kolom|Kolommen',

        'form_description' => [
            'general'                   => 'Stel de standaardinstellingen in voor de opmaak van uw factuurnummers en betalingsvoorwaarden.',
            'template'                  => 'Selecteer een van de onderstaande sjablonen voor uw facturen.',
            'default'                   => 'Als u standaardinstellingen voor facturen selecteert, worden titels, subkoppen, notities en voetteksten vooraf ingevuld. U hoeft dus niet elke keer facturen te bewerken om er professioneler uit te zien.',
            'column'                    => 'Pas de naam van de factuurkolommen aan. Als u artikelomschrijvingen en bedragen in regels wilt verbergen, kunt u dit hier wijzigen.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Kies het overdrachtssjabloon',
        'second'                        => 'Tweede',
        'third'                         => 'Derde',
    ],

    'default' => [
        'description'                   => 'Standaard rekening, valuta, taal van uw bedrijf',
        'search_keywords'               => 'rekening, valuta, taal, belasting, betaling, methode, paginering',
        'list_limit'                    => 'Resultaten per pagina',
        'use_gravatar'                  => 'Gebruik Gravatar',
        'income_category'               => 'Inkomenscategorie',
        'expense_category'              => 'Onkostencategorie',
        'address_format'                => 'Adres Formaat',
        'address_tags'                  => '<strong>Beschikbare tags:</strong> :tags',

        'form_description' => [
            'general'                   => 'Selecteer de standaard rekening, belasting en betalingsmethode om snel records te creëren. Dashboard en rapporten worden weergegeven onder de standaardvaluta.',
            'category'                  => 'Selecteer de standaardcategorieën om het maken van records te versnellen.',
            'other'                     => 'Pas de standaardinstellingen van de bedrijfstaal aan en hoe paginering werkt.',
        ],
    ],

    'email' => [
        'description'                   => 'Wijzig de verzendprotocol en e-mailsjablonen',
        'search_keywords'               => 'e-mail, verzenden, protocol, smtp, host, wachtwoord',
        'protocol'                      => 'Protocol',
        'php'                           => 'PHP mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP host',
            'port'                      => 'SMTP-poort',
            'username'                  => 'SMTP gebruikersnaam',
            'password'                  => 'SMTP wachtwoord',
            'encryption'                => 'SMTP beveiliging',
            'none'                      => 'Geen',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail pad',
        'log'                           => 'E-mail logs',
        'email_service'                 => 'E-mailservice',
        'email_templates'               => 'E-mailsjablonen',

        'form_description' => [
            'general'                   => 'Stuur regelmatig e-mails naar uw team en contacten. U kunt het protocol en de SMTP-instellingen instellen.',
        ],

        'templates' => [
            'description'               => 'De e-mailsjablonen wijzigen',
            'search_keywords'           => 'e-mail, sjabloon, onderwerp, hoofdtekst, tag',
            'subject'                   => 'Onderwerp',
            'body'                      => 'Inhoud',
            'tags'                      => '<strong>Beschikbare tags:</strong> :tag_list',
            'invoice_new_customer'      => 'Nieuw factuursjabloon (verzonden naar de klant)',
            'invoice_remind_customer'   => 'Factuurherinneringssjabloon (verzonden naar de klant)',
            'invoice_remind_admin'      => 'Factuurherinneringssjabloon (verzonden naar de beheerder)',
            'invoice_recur_customer'    => 'Terugkerende factuursjabloon (verzonden naar de klant)',
            'invoice_recur_admin'       => 'Terugkerende factuursjabloon (verzonden naar de admin)',
            'invoice_view_admin'        => 'Factuurweergavesjabloon (verzonden naar de beheerder)',
            'invoice_payment_customer'  => 'Betaling ontvangen sjabloon (verzonden naar de klant)',
            'invoice_payment_admin'     => 'Betaling ontvangen sjabloon (verzonden naar de admin)',
            'bill_remind_admin'         => 'Sjabloon voor onkostenfactuur (verzonden naar de beheerder)',
            'bill_recur_admin'          => 'Sjabloon voor terugkerende onkostenfactuur (verzonden naar de beheerder)',
            'payment_received_customer' => 'Betaalbewijssjabloon (verzonden naar de klant)',
            'payment_made_vendor'       => 'Betaling gemaakt sjabloon (verzonden naar de leverancier)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Planning',
        'description'                   => 'Automatische herinneringen en abonnementsfacturen',
        'search_keywords'               => 'automatisch, herinnering, terugkerend, cron, commando',
        'send_invoice'                  => 'Factuur herinnering sturen',
        'invoice_days'                  => 'Aantal dagen na vervaldatum sturen',
        'send_bill'                     => 'Factuur herinnering sturen',
        'bill_days'                     => 'Aantal dagen voor vervaldatum sturen',
        'cron_command'                  => 'Cron opdracht',
        'command'                       => 'Opdracht',
        'schedule_time'                 => 'Uren duurtijd',

        'form_description' => [
            'invoice'                   => 'Schakel in of uit en stel herinneringen in voor uw facturen wanneer ze te laat zijn.',
            'bill'                      => 'Schakel in of uit en stel herinneringen in voor uw facturen voordat ze te laat zijn.',
            'cron'                      => 'Kopieer de cron-opdracht die uw server moet uitvoeren. Stel de tijd in om de gebeurtenis te activeren.',
        ]
    ],

    'categories' => [
        'description'                   => 'Onbeperkt aantal categorieën voor inkomen, uitgaven en producten',
        'search_keywords'               => 'categorie, inkomsten, uitgaven, item',
    ],

    'currencies' => [
        'description'                   => 'Maak en beheer valuta en stel de koersen in',
        'search_keywords'               => 'standaard, valuta, valuta, code, koers, symbool, precisie, positie, decimaal, duizendtallen, teken, scheidingsteken',
    ],

    'taxes' => [
        'description'                   => 'Vaste normaal, inclusief, en samengestelde belastingtarieven',
        'search_keywords'               => 'belasting, tarief, type, vast, inclusief, samengesteld, inhouding',
    ],

];
