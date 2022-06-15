<?php

return [

    'company' => [
        'description'                   => 'Nomainiet kompānijas nosaukumu, e-pastu, adresi, nodokļu maksātāja nr. u.c.',
        'search_keywords'               => 'uzņēmums, nosaukums, e-pasts, tālrunis, adrese, valsts, nodokļu numurs, logotips, pilsēta, pilsēta, štats, province, pasta indekss',
        'name'                          => 'Vārds',
        'email'                         => 'E-pasts',
        'phone'                         => 'Tālrunis',
        'address'                       => 'Adrese',
        'edit_your_business_address'    => 'Rediģējiet uzņēmuma adresi',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Šī informācija ir redzama jūsu izveidotajos ierakstos.',
            'address'                   => 'Adrese tiks izmantota jūsu izrakstītajos rēķinos, saņemtajos rēķinos un citos ierakstos.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Noteikt fiskālo gadu, laika zonu, datuma formātu un citas lokālās vienības',
        'search_keywords'               => 'finanšu, gads, sākums, norādīt, laiks, zona, datums, formāts, atdalītājs, atlaide, procenti',
        'financial_start'               => 'Finansiālā gada sākums',
        'timezone'                      => 'Laika zona',
        'financial_denote' => [
            'title'                     => 'Finanšu gada norāde',
            'begins'                    => 'Pēc gada, kurā tas sākas',
            'ends'                      => 'Līdz gadam, kad tas beidzas',
        ],
        'preferred_date'                => 'Vēlamais datums',
        'date' => [
            'format'                    => 'Datuma formāts',
            'separator'                 => 'Datuma atdalītājs',
            'dash'                      => 'Domuzīme (-)',
            'dot'                       => 'Punkts (.)',
            'comma'                     => 'Komats (,)',
            'slash'                     => 'Daļsvītra (/)',
            'space'                     => 'Atstarpe ( )',
        ],
        'percent' => [
            'title'                     => 'Procentu (%) pozīcija',
            'before'                    => 'Pirms skaitļa',
            'after'                     => 'Pēc skaitļa',
        ],
        'discount_location' => [
            'name'                      => 'Atlaides atrašanās vieta',
            'item'                      => 'Uz līnijas',
            'total'                     => 'Kopumā',
            'both'                      => 'Gan uz līnijas, gan kopā',
        ],

        'form_description' => [
            'fiscal'                    => 'Iestatiet finanšu gada periodu, ko jūsu uzņēmums izmanto nodokļu aplikšanai un pārskatu iesniegšanai.',
            'date'                      => 'Atlasiet datuma formātu, ko vēlaties redzēt visur saskarnē.',
            'other'                     => 'Atlasiet, kur tiek rādīta nodokļu procentu zīme. Varat iespējot atlaides rindas vienībām un rēķinu un rēķinu kopsummai.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Rediģēt pavadzīmju artikulus, numerāciju, terminus, kājeni (footer) u.c.',
        'search_keywords'               => 'pielāgot, rēķins, numurs, prefikss, cipars, nākamais, logotips, nosaukums, cena, daudzums, veidne, virsraksts, apakšvirsraksts, kājene, piezīme, slēpt, termiņš, krāsa, maksājums, noteikumi, kolonna',
        'prefix'                        => 'Rēķina sākums',
        'digit'                         => 'Rēķina numura garums',
        'next'                          => 'Nākamais numurs',
        'logo'                          => 'Logo',
        'custom'                        => 'Pasūtījuma',
        'item_name'                     => 'Lietas nosaukums',
        'item'                          => 'Lietas / Preces',
        'product'                       => 'Produkti',
        'service'                       => 'Servisi',
        'price_name'                    => 'Cenas nosaukums',
        'price'                         => 'Cena',
        'rate'                          => 'Attiecība',
        'quantity_name'                 => 'Daudzuma nosaukums',
        'quantity'                      => 'Daudzums',
        'payment_terms'                 => 'Maksājuma termini',
        'title'                         => 'Nosaukums',
        'subheading'                    => 'Apakš-nosaukums',
        'due_receipt'                   => 'Termiņš līdz saņemšanai',
        'due_days'                      => 'Izpildes laiks, dienu skaits',
        'choose_template'               => 'Izvēlēties pavadzīmes sagatavi',
        'default'                       => 'Noklusējuma',
        'classic'                       => 'Klasisks',
        'modern'                        => 'Moderns',
        'hide' => [
            'item_name'                 => 'Paslēpt vienuma nosaukumu',
            'item_description'          => 'Paslēpt vienuma aprakstu',
            'quantity'                  => 'Paslēpt daudzumu',
            'price'                     => 'Paslēpt cenu',
            'amount'                    => 'Paslēpt summu',
        ],
        'column'                        => 'Kolonna|Kolonnas',

        'form_description' => [
            'general'                   => 'Iestatiet noklusējuma iestatījumus rēķinu numuru un maksājuma noteikumu formatēšanai.',
            'template'                  => 'Izvēlieties kādu no tālāk norādītajām veidnēm saviem rēķiniem.',
            'default'                   => 'Izvēloties rēķinu noklusējuma iestatījumus, virsraksti, apakšvirsraksti, piezīmes un kājenes tiks aizpildītas iepriekš. Tāpēc jums nav katru reizi jārediģē rēķini, lai izskatītos profesionālāk.',
            'column'                    => 'Pielāgojiet rēķinu sleju nosaukumus. Ja vēlaties paslēpt preču aprakstus un summas rindās, varat to mainīt šeit.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Izvēlēties pārsūtīšanas veidni',
        'second'                        => 'Otrais',
        'third'                         => 'Trešais',
    ],

    'default' => [
        'description'                   => 'Jūsu uzņēmuma noklusējuma konts, valūta un valoda',
        'search_keywords'               => 'konts, valūta, valoda, nodoklis, maksājums, metode, lappuse',
        'list_limit'                    => 'Ierakstu skaits lapā',
        'use_gravatar'                  => 'Izmantot Gravatar',
        'income_category'               => 'Ieņēmumu kategorijas',
        'expense_category'              => 'Izdevumu kategorijas',

        'form_description' => [
            'general'                   => 'Atlasiet noklusējuma kontu, nodokļus un maksājuma veidu, lai ātri izveidotu ierakstus. Informācijas panelis un pārskati tiek rādīti noklusējuma valūtā.',
            'category'                  => 'Atlasiet noklusējuma kategorijas, lai paātrinātu ieraksta izveidi.',
            'other'                     => 'Pielāgojiet uzņēmuma valodas noklusējuma iestatījumus un to, kā darbojas lappušu veidošana.',
        ],
    ],

    'email' => [
        'description'                   => 'Nomainīt uzsūtnes protokolu un e-pasta sagataves',
        'search_keywords'               => 'e-pasts, sūtīšana, protokols, smtp, resursdators, parole',
        'protocol'                      => 'Protokols',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP adrese',
            'port'                      => 'SMTP ports',
            'username'                  => 'SMTP lietotājs',
            'password'                  => 'SMTP parole',
            'encryption'                => 'SMTP drošība',
            'none'                      => 'nav',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail ceļš',
        'log'                           => 'Auditēt e-pastus',
        'email_service'                 => 'E-pasta pakalpojums',
        'email_templates'               => 'E-pasta veidnes',

        'form_description' => [
            'general'                   => 'Regulāri sūtiet e-pasta ziņojumus savai komandai un kontaktpersonām. Varat iestatīt protokolu un SMTP iestatījumus.',
        ],

        'templates' => [
            'description'               => 'Izveidot vēstules šablonu',
            'search_keywords'           => 'e-pasts, veidne, tēma, pamatteksts, tags',
            'subject'                   => 'Nosaukums / Tēma',
            'body'                      => 'Galvenā daļa',
            'tags'                      => '<strong>Pieejamās atzīmes:</strong> :tag_list',
            'invoice_new_customer'      => 'Jaunas pavadzīmes sagatave (nosūtīts klientam)',
            'invoice_remind_customer'   => 'Atgādinājums par pavadzīmi (nosūtīts klientam)',
            'invoice_remind_admin'      => 'Atgādinājums par pavadzīmi (nosūtīts administrātoram)',
            'invoice_recur_customer'    => 'Atkārtotas pavadzīmes sagatave (nosūtīts klientam)',
            'invoice_recur_admin'       => 'Atkārtotas pavadzīmes sagatave (nosūtīts administrātoram)',
            'invoice_view_admin'        => 'Rēķina skata veidne (nosūtīta administratoram)',
            'invoice_payment_customer'  => '"Maksājums saņemts" sagatave (nosūtīts klientam)',
            'invoice_payment_admin'     => '"Maksājums saņemts" sagatave (nosūtīts administrātoram)',
            'bill_remind_admin'         => '"Atgādinājums par rēķinu" sagatave (nosūtīts administrātoram)',
            'bill_recur_admin'          => 'Atkārtota rēķina sagatave (nosūtīts administrātoram)',
            'payment_received_customer' => 'Maksājuma kvīts veidne (nosūtīta klientam)',
            'payment_made_vendor'       => 'Maksājuma veidne (nosūtīta piegādātājam)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Plānošana',
        'description'                   => 'Automātiskie atgādinātāji un komandas atkārtojumu veikšanai',
        'search_keywords'               => 'automātisks, atgādinājums, atkārtots, cron, komanda',
        'send_invoice'                  => 'Sūtīt rēķinu atgādinājumus',
        'invoice_days'                  => 'Sūtīt pēc kavētām dienām',
        'send_bill'                     => 'Sūtīt piegādātāju rēķinu atgādinājumus',
        'bill_days'                     => 'Sūtīt dienas pirms termiņa',
        'cron_command'                  => 'Cron komanda',
        'command'                       => 'Komanda',
        'schedule_time'                 => 'Stunda kurā sūtīt',

        'form_description' => [
            'invoice'                   => 'Iespējojiet vai atspējojiet un iestatiet atgādinājumus saviem rēķiniem, kad tie ir nokavēti.',
            'bill'                      => 'Iespējojiet vai atspējojiet un iestatiet atgādinājumus saviem rēķiniem, pirms tie ir nokavēti.',
            'cron'                      => 'Nokopējiet komandu cron, kas jāpalaiž jūsu serverim. Iestatiet notikuma aktivizēšanas laiku.',
        ]
    ],

    'categories' => [
        'description'                   => 'Bezizmēra kategorijas ienākumiem, izdevumiem un priekšmetiem',
        'search_keywords'               => 'kategorija, ienākumi, izdevumi, prece',
    ],

    'currencies' => [
        'description'                   => 'Uzstādīt un pārvaldīt valūtas un to attiecību',
        'search_keywords'               => 'noklusējuma, valūta, valūtas, kods, kurss, simbols, precizitāte, pozīcija, decimāldaļa, tūkstoši, atzīme, atdalītājs',
    ],

    'taxes' => [
        'description'                   => 'Fiksētas, ierastas, ietverošas un apvienotas nodokļu likmes',
        'search_keywords'               => 'nodoklis, likme, veids, fiksēts, ieskaitot, salikts, ieturējums',
    ],

];
