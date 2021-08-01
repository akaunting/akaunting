<?php

return [

    'company' => [
        'description'                => 'Endre bedriftsnavn, e-post, adresse, momsavgifter mm',
        'name'                       => 'Navn',
        'email'                      => 'E-post',
        'phone'                      => 'Telefon',
        'address'                    => 'Adresse',
        'edit_your_business_address' => 'Rediger forretningsadressen din',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Sett regnskapsår, tidssone, datoformat og mer',
        'financial_start'   => 'Start på regnskapsår',
        'timezone'          => 'Tidssone',
        'financial_denote' => [
            'title'         => 'Betegnelse regnskapsår',
            'begins'        => 'Til starten av året',
            'ends'          => 'Til slutten av året',
        ],
        'date' => [
            'format'        => 'Datoformat',
            'separator'     => 'Datoseparator',
            'dash'          => 'Bindestrek (-)',
            'dot'           => 'Punktum (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Skråstrek (/)',
            'space'         => 'Mellomrom ( )',
        ],
        'percent' => [
            'title'         => 'Prosentplassering (%)',
            'before'        => 'Før nummer',
            'after'         => 'Etter nummer',
        ],
        'discount_location' => [
            'name'          => 'Rabatt plassering',
            'item'          => 'På linje',
            'total'         => 'På total',
            'both'          => 'Både linje og total',
        ],
    ],

    'invoice' => [
        'description'       => 'Tilpass fakturaprefiks, nummer, vilkår, bunntekst mm',
        'prefix'            => 'Nummerprefiks',
        'digit'             => 'Antall siffer',
        'next'              => 'Neste nummer',
        'logo'              => 'Logo',
        'custom'            => 'Tilpasset',
        'item_name'         => 'Artikkelnavn',
        'item'              => 'Artikler',
        'product'           => 'Produkter',
        'service'           => 'Tjenester',
        'price_name'        => 'Prisnavn',
        'price'             => 'Pris',
        'rate'              => 'Sats',
        'quantity_name'     => 'Kvantitetsnavn',
        'quantity'          => 'Kvantitet',
        'payment_terms'     => 'Betalingsvilkår',
        'title'             => 'Tittel',
        'subheading'        => 'Underoverskrift',
        'due_receipt'       => 'Forfall ved mottak',
        'due_days'          => 'Forfall innen :days dager',
        'choose_template'   => 'Velg fakturamal',
        'default'           => 'Standard',
        'classic'           => 'Klassisk',
        'modern'            => 'Moderne',
        'hide'              => [
            'item_name'         => 'Skjul elementnavn',
            'item_description'  => 'Skjul elementbeskrivelse',
            'quantity'          => 'Skjul antall',
            'price'             => 'Skjul pris',
            'amount'            => 'Skjul beløp',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Velg overføringsmal',
        'second'            => 'Sekund',
        'third'             => 'Tredje',
    ],

    'default' => [
        'description'       => 'Standard konto, valuta, språk for ditt foretak',
        'list_limit'        => 'Oppføringer per side',
        'use_gravatar'      => 'Bruk Gravatar',
        'income_category'   => 'Inntektskategori',
        'expense_category'  => 'Utgiftskategori',
    ],

    'email' => [
        'description'       => 'Endre sendingsprotokoll og e-postmaler',
        'protocol'          => 'Protokoll',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP-vert',
            'port'          => 'SMTP-port',
            'username'      => 'SMTP-brukernavn',
            'password'      => 'SMTP-passord',
            'encryption'    => 'SMTP-sikkerhet',
            'none'          => 'Ingen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sti for Sendmail',
        'log'               => 'Logg e-post',

        'templates' => [
            'subject'                   => 'Emne',
            'body'                      => 'Hoveddel',
            'tags'                      => '<strong>Tilgjengelige tagger:</strong> :tag_list',
            'invoice_new_customer'      => 'Ny fakturamal (sendt til kunde)',
            'invoice_remind_customer'   => 'Fakturapåminnelsesmal (sendt til kunde)',
            'invoice_remind_admin'      => 'Fakturapåminnelsesmal (sendt til admin)',
            'invoice_recur_customer'    => 'Gjentagende fakturamal (sendt til kunde)',
            'invoice_recur_admin'       => 'Gjentagende fakturamal (sendt til admin)',
            'invoice_payment_customer'  => 'Mottatt betalingsmal (sendt til kunde)',
            'invoice_payment_admin'     => 'Mottatt betalingsmal (sendt til admin)',
            'bill_remind_admin'         => 'Fakturapåminnelsesmal (sendt til admin)',
            'bill_recur_admin'          => 'Gjentagende fakturamal (sendt til admin)',
            'revenue_new_customer'      => 'Mal mottatte inntekter (sendt til kunde)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Tidsplan',
        'description'       => 'Automatiske påminnelser og kommandoer for gjentakende aktiviteter',
        'send_invoice'      => 'Send fakturapåminnelse',
        'invoice_days'      => 'Antall dager etter forfall for utsending',
        'send_bill'         => 'Send fakturapåminnelse',
        'bill_days'         => 'Antall dager før forfall for utsending',
        'cron_command'      => 'Cron-kommando',
        'schedule_time'     => 'Tid for kjøring',
    ],

    'categories' => [
        'description'       => 'Kategorier for inntekter, utgifter og artikler',
    ],

    'currencies' => [
        'description'       => 'Opprette og behandle valuta',
    ],

    'taxes' => [
        'description'       => 'Faste, normale, inklusive og forbundne avgifter',
    ],

];
