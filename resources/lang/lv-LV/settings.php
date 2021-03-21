<?php

return [

    'company' => [
        'description'       => 'Nomainiet kompānijas nosaukumu, e-pastu, adresi, nodokļu maksātāja nr. u.c.',
        'name'              => 'Vārds',
        'email'             => 'E-pasts',
        'phone'             => 'Tālrunis',
        'address'           => 'Adrese',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Noteikt fiskālo gadu, laika zonu, datuma formātu un citas lokālās vienības',
        'financial_start'   => 'Finansiālā gada sākums',
        'timezone'          => 'Laika zona',
        'date' => [
            'format'        => 'Datuma formāts',
            'separator'     => 'Datuma atdalītājs',
            'dash'          => 'Domuzīme (-)',
            'dot'           => 'Punkts (.)',
            'comma'         => 'Komats (,)',
            'slash'         => 'Daļsvītra (/)',
            'space'         => 'Atstarpe ( )',
        ],
        'percent' => [
            'title'         => 'Procentu (%) pozīcija',
            'before'        => 'Pirms skaitļa',
            'after'         => 'Pēc skaitļa',
        ],
    ],

    'invoice' => [
        'description'       => 'Rediģēt pavadzīmju artikulus, numerāciju, terminus, kājeni (footer) u.c.',
        'prefix'            => 'Rēķina sākums',
        'digit'             => 'Rēķina numura garums',
        'next'              => 'Nākamais numurs',
        'logo'              => 'Logo',
        'custom'            => 'Pasūtījuma',
        'item_name'         => 'Lietas nosaukums',
        'item'              => 'Lietas / Preces',
        'product'           => 'Produkti',
        'service'           => 'Servisi',
        'price_name'        => 'Cenas nosaukums',
        'price'             => 'Cena',
        'rate'              => 'Attiecība',
        'quantity_name'     => 'Daudzuma nosaukums',
        'quantity'          => 'Daudzums',
        'payment_terms'     => 'Maksājuma termini',
        'title'             => 'Nosaukums',
        'subheading'        => 'Apakš-nosaukums',
        'due_receipt'       => 'Termiņš līdz saņemšanai',
        'due_days'          => 'Izpildes laiks, dienu skaits',
        'choose_template'   => 'Izvēlēties pavadzīmes sagatavi',
        'default'           => 'Noklusējuma',
        'classic'           => 'Klasisks',
        'modern'            => 'Moderns',
    ],

    'default' => [
        'description'       => 'Jūsu uzņēmuma noklusējuma konts, valūta un valoda',
        'list_limit'        => 'Ierakstu skaits lapā',
        'use_gravatar'      => 'Use Gravatar',
    ],

    'email' => [
        'description'       => 'Nomainīt uzsūtnes protokolu un e-pasta sagataves',
        'protocol'          => 'Protokols',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP adrese',
            'port'          => 'SMTP ports',
            'username'      => 'SMTP lietotājs',
            'password'      => 'SMTP parole',
            'encryption'    => 'SMTP drošība',
            'none'          => 'nav',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail ceļš',
        'log'               => 'Auditēt e-pastus',

        'templates' => [
            'subject'                   => 'Nosaukums / Tēma',
            'body'                      => 'Galvenā daļa',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'Jaunas pavadzīmes sagatave (nosūtīts klientam)',
            'invoice_remind_customer'   => 'Atgādinājums par pavadzīmi (nosūtīts klientam)',
            'invoice_remind_admin'      => 'Atgādinājums par pavadzīmi (nosūtīts administrātoram)',
            'invoice_recur_customer'    => 'Atkārtotas pavadzīmes sagatave (nosūtīts klientam)',
            'invoice_recur_admin'       => 'Atkārtotas pavadzīmes sagatave (nosūtīts administrātoram)',
            'invoice_payment_customer'  => '"Maksājums saņemts" sagatave (nosūtīts klientam)',
            'invoice_payment_admin'     => '"Maksājums saņemts" sagatave (nosūtīts administrātoram)',
            'bill_remind_admin'         => '"Atgādinājums par rēķinu" sagatave (nosūtīts administrātoram)',
            'bill_recur_admin'          => 'Atkārtota rēķina sagatave (nosūtīts administrātoram)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Ieplānotšana',
        'description'       => 'Automātiskie atgādinātāji un komandas atkārtojumu veikšanai',
        'send_invoice'      => 'Sūtīt rēķinu atgādinājumus',
        'invoice_days'      => 'Sūtīt pēc kavētām dienām',
        'send_bill'         => 'Sūtīt piegādātāju rēķinu atgādinājumus',
        'bill_days'         => 'Sūtīt dienas pirms termiņa',
        'cron_command'      => 'Cron komanda',
        'schedule_time'     => 'Stunda kurā sūtīt',
    ],

    'categories' => [
        'description'       => 'Bezizmēra kategorijas ienākumiem, izdevumiem un priekšmetiem',
    ],

    'currencies' => [
        'description'       => 'Uzstādīt un pārvaldīt valūtas un to attiecību',
    ],

    'taxes' => [
        'description'       => 'Fiksētas, ierastas, ietverošas un apvienotas nodokļu likmes',
    ],

];
