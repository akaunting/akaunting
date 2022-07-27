<?php

return [

    'company' => [
        'description'                   => 'Endre bedriftsnavn, e-post, adresse, momsavgifter mm',
        'search_keywords'               => 'bedrift, e-post, telefon, adresse, land, organisasjonsnummer, logo, by, tettsted, fylke, postnummer',
        'name'                          => 'Navn',
        'email'                         => 'E-post',
        'phone'                         => 'Telefon',
        'address'                       => 'Adresse',
        'edit_your_business_address'    => 'Rediger forretningsadressen din',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Denne informasjonen er synlig i postene du lager.',
            'address'                   => 'Adressen blir brukt i fakturaer, regninger og andre poster som du har skrevet.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Sett regnskapsår, tidssone, datoformat og mer',
        'search_keywords'               => 'økonomi, år, start, denote, tid, sone, dato, format, separator, rabatt, prosent',
        'financial_start'               => 'Start på regnskapsår',
        'timezone'                      => 'Tidssone',
        'financial_denote' => [
            'title'                     => 'Betegnelse regnskapsår',
            'begins'                    => 'Til starten av året',
            'ends'                      => 'Til slutten av året',
        ],
        'preferred_date'                => 'Foretrukket dato',
        'date' => [
            'format'                    => 'Datoformat',
            'separator'                 => 'Datoseparator',
            'dash'                      => 'Bindestrek (-)',
            'dot'                       => 'Punktum (.)',
            'comma'                     => 'Komma (,)',
            'slash'                     => 'Skråstrek (/)',
            'space'                     => 'Mellomrom ( )',
        ],
        'percent' => [
            'title'                     => 'Prosentplassering (%)',
            'before'                    => 'Før nummer',
            'after'                     => 'Etter nummer',
        ],
        'discount_location' => [
            'name'                      => 'Rabatt plassering',
            'item'                      => 'På linje',
            'total'                     => 'På total',
            'both'                      => 'Både linje og total',
        ],

        'form_description' => [
            'fiscal'                    => 'Angi regnskapsårets periode som bedriften bruker for beskatning og rapportering.',
            'date'                      => 'Velg datoformatet du vil se overalt i grensesnittet.',
            'other'                     => 'Velg hvor prosentandelskiltet vises for avgifter. Du kan aktivere rabatter på linjeelementer og på summen av fakturaer og regninger.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Tilpass fakturaprefiks, nummer, vilkår, bunntekst mm',
        'search_keywords'               => 'tilpass, fakture, nummer, prefiks, tall, neste, logo, navn, pris, mengde, mal, tittel, underoverskrift, bunntekst, notat, skjema, forfall, farge, betaling, vilkår, kolonner',
        'prefix'                        => 'Nummerprefiks',
        'digit'                         => 'Antall siffer',
        'next'                          => 'Neste nummer',
        'logo'                          => 'Logo',
        'custom'                        => 'Tilpasset',
        'item_name'                     => 'Artikkelnavn',
        'item'                          => 'Artikler',
        'product'                       => 'Produkter',
        'service'                       => 'Tjenester',
        'price_name'                    => 'Prisnavn',
        'price'                         => 'Pris',
        'rate'                          => 'Sats',
        'quantity_name'                 => 'Kvantitetsnavn',
        'quantity'                      => 'Kvantitet',
        'payment_terms'                 => 'Betalingsvilkår',
        'title'                         => 'Tittel',
        'subheading'                    => 'Underoverskrift',
        'due_receipt'                   => 'Forfall ved mottak',
        'due_days'                      => 'Forfall innen :days dager',
        'choose_template'               => 'Velg fakturamal',
        'default'                       => 'Standard',
        'classic'                       => 'Klassisk',
        'modern'                        => 'Moderne',
        'hide' => [
            'item_name'                 => 'Skjul elementnavn',
            'item_description'          => 'Skjul elementbeskrivelse',
            'quantity'                  => 'Skjul antall',
            'price'                     => 'Skjul pris',
            'amount'                    => 'Skjul beløp',
        ],
        'column'                        => 'Kolonne|Kolonner',

        'form_description' => [
            'general'                   => 'Sett standarder for formatering av fakturanumrene og betalingsbetingelsene.',
            'template'                  => 'Velg en av malene nedenfor for dine fakturaer.',
            'default'                   => 'Valg av standardinnstillinger for fakturaer vil forhåndsutfylle titler, underoverskrifter, notater og bunntekster. Da trenger du ikke å redigere fakturaer hver gang for at det skal se profesjonelt ut.',
            'column'                    => 'Tilpass hvordan faktura-kolonnene blir navngitt. Hvis du vil skjule varebeskrivelser og beløp i linjer, kan du endre dem her.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Velg overføringsmal',
        'second'                        => 'Sekund',
        'third'                         => 'Tredje',
    ],

    'default' => [
        'description'                   => 'Standard konto, valuta, språk for ditt foretak',
        'search_keywords'               => 'konto, valuta, språk, avgift, betaling, metode, sideinndeling',
        'list_limit'                    => 'Oppføringer per side',
        'use_gravatar'                  => 'Bruk Gravatar',
        'income_category'               => 'Inntektskategori',
        'expense_category'              => 'Utgiftskategori',

        'form_description' => [
            'general'                   => 'Velg standardkonto, skatt og betalingsmetode for å opprette poster straks. Dashbord og rapporter vises i standardvalutaen.',
            'category'                  => 'Velg standardkategorier for å forenkle opprettingen av posten.',
            'other'                     => 'Tilpass standardinnstillingene for selskapets språk, og hvordan paginering virker. ',
        ],
    ],

    'email' => [
        'description'                   => 'Endre sendingsprotokoll og e-postmaler',
        'search_keywords'               => 'e-post, send, protokoll, smtp, verter, passord',
        'protocol'                      => 'Protokoll',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP-vert',
            'port'                      => 'SMTP-port',
            'username'                  => 'SMTP-brukernavn',
            'password'                  => 'SMTP-passord',
            'encryption'                => 'SMTP-sikkerhet',
            'none'                      => 'Ingen',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sti for Sendmail',
        'log'                           => 'Logg e-post',
        'email_service'                 => 'E-post-tjeneste',
        'email_templates'               => 'E-postmaler',

        'form_description' => [
            'general'                   => 'Send vanlige e-poster til ditt team og kontakter. Du kan angi protokollen og SMTP innstillingene.',
        ],

        'templates' => [
            'description'               => 'Endre e-postmaler',
            'search_keywords'           => 'e-post, mal, person, del, tag',
            'subject'                   => 'Emne',
            'body'                      => 'Hoveddel',
            'tags'                      => '<strong>Tilgjengelige tagger:</strong> :tag_list',
            'invoice_new_customer'      => 'Ny fakturamal (sendt til kunde)',
            'invoice_remind_customer'   => 'Fakturapåminnelsesmal (sendt til kunde)',
            'invoice_remind_admin'      => 'Fakturapåminnelsesmal (sendt til admin)',
            'invoice_recur_customer'    => 'Gjentagende fakturamal (sendt til kunde)',
            'invoice_recur_admin'       => 'Gjentagende fakturamal (sendt til admin)',
            'invoice_view_admin'        => 'Fakturavisningsmal (sendt til admin)',
            'invoice_payment_customer'  => 'Mottatt betalingsmal (sendt til kunde)',
            'invoice_payment_admin'     => 'Mottatt betalingsmal (sendt til admin)',
            'bill_remind_admin'         => 'Fakturapåminnelsesmal (sendt til admin)',
            'bill_recur_admin'          => 'Gjentagende fakturamal (sendt til admin)',
            'payment_received_customer' => 'Betalingsmottaksmal (sendt til kunde)',
            'payment_made_vendor'       => 'Betaling gjort Mal (sendt til leverandør)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Tidsplan',
        'description'                   => 'Automatiske påminnelser og kommandoer for gjentakende aktiviteter',
        'search_keywords'               => 'automatisk, påminnelse, gjentakende, cron, kommando',
        'send_invoice'                  => 'Send fakturapåminnelse',
        'invoice_days'                  => 'Antall dager etter forfall for utsending',
        'send_bill'                     => 'Send fakturapåminnelse',
        'bill_days'                     => 'Antall dager før forfall for utsending',
        'cron_command'                  => 'Cron-kommando',
        'command'                       => 'Kommando',
        'schedule_time'                 => 'Tid for kjøring',

        'form_description' => [
            'invoice'                   => 'Aktivere eller deaktivere og angi påminnelser for fakturaene dine når de forfaller.',
            'bill'                      => 'Aktivere eller deaktivere og angi påminnelser for fakturaene dine før de forfaller.',
            'cron'                      => 'Kopier cron-kommandoen som serveren skal kjøre. Angi tidspunktet for å utløse hendelsen.',
        ]
    ],

    'categories' => [
        'description'                   => 'Kategorier for inntekter, utgifter og artikler',
        'search_keywords'               => 'kategori, inntekt, utgift, enhet',
    ],

    'currencies' => [
        'description'                   => 'Opprette og behandle valuta',
        'search_keywords'               => 'standard , valuta, valuta, kode, pris, symbol, presisjon, posisjon, desimal, tusen, merke, separator',
    ],

    'taxes' => [
        'description'                   => 'Faste, normale, inklusive og forbundne avgifter',
        'search_keywords'               => 'avgift, pris, type, fastsatt, inkludert, forbindelse, tilbakeholdelse',
    ],

];
