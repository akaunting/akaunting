<?php

return [

    'company' => [
        'description'                => 'Muutke ettevõtte nime, e-posti aadressi, maksunumbrit jne',
        'name'                       => 'Nimi',
        'email'                      => 'E-post',
        'phone'                      => 'Telefon',
        'address'                    => 'Aadress',
        'edit_your_business_address' => 'Muutke oma ettevõtte aadressi',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Määra eelarveaasta, ajavöönd, kuupäevavorming ja muud',
        'financial_start'   => 'Majandusaasta algus',
        'timezone'          => 'Ajavöönd',
        'financial_denote' => [
            'title'         => 'Majandusaasta tähistab',
            'begins'        => 'Majandusaasta algus',
            'ends'          => 'Majandusaasta lõpp',
        ],
        'date' => [
            'format'        => 'Kuupäeva vorming',
            'separator'     => 'Kuupäeva eraldaja',
            'dash'          => 'Kriips (-)',
            'dot'           => 'Punkt (.)',
            'comma'         => 'Koma (,)',
            'slash'         => 'Kaldkriips (/)',
            'space'         => 'Tühik ( )',
        ],
        'percent' => [
            'title'         => 'Protsendi (%) asukoht',
            'before'        => 'Numbri ees',
            'after'         => 'Numbri järel',
        ],
        'discount_location' => [
            'name'          => 'Allahindluse asukoht',
            'item'          => 'Reas',
            'total'         => 'Kokku',
            'both'          => 'Nii rida kui ka kokku',
        ],
    ],

    'invoice' => [
        'description'       => 'Kohanda arve prefiksit, numbrit, termineid, jalust jne',
        'prefix'            => 'Numbri eesliide',
        'digit'             => 'Piiritleja',
        'next'              => 'Järgmine number',
        'logo'              => 'Logo',
        'custom'            => 'Kohandatud',
        'item_name'         => 'Ühiku nimi',
        'item'              => 'Kirjet',
        'product'           => 'Tooted',
        'service'           => 'Teenused',
        'price_name'        => 'Hinna nimetus',
        'price'             => 'Hind',
        'rate'              => 'Kurss',
        'quantity_name'     => 'Koguse nimetus',
        'quantity'          => 'Kogus',
        'payment_terms'     => 'Maksetingimused',
        'title'             => 'Pealkiri',
        'subheading'        => 'Alapealkiri',
        'due_receipt'       => 'Tähtaeg saabumisel',
        'due_days'          => 'Tähtaeg: :days päeva',
        'choose_template'   => 'Valige arve šabloon',
        'default'           => 'Vaike',
        'classic'           => 'Klassikaline',
        'modern'            => 'Kaasaegne',
        'hide'              => [
            'item_name'         => 'Peida Ese Nimi',
            'item_description'  => 'Peida Ese Kirjeldus',
            'quantity'          => 'Peida Kogus',
            'price'             => 'Peida Hind',
            'amount'            => 'Peida summa',
        ],
    ],

    'default' => [
        'description'       => 'Teie ettevõtte vaikekonto, valuuta ja keel',
        'list_limit'        => 'Kirjeid leheküljel',
        'use_gravatar'      => 'Kasuta Gravatari',
        'income_category'   => 'Sissetulekud kategooria järgi',
        'expense_category'  => 'Kulud kategooriate järgi',
    ],

    'email' => [
        'description'       => 'Saatjaprotokolli ja e-posti šabloonide muutmine',
        'protocol'          => 'Protokoll',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP host',
            'port'          => 'SMTP port',
            'username'      => 'SMTP kasutajanimi',
            'password'      => 'SMTP parool',
            'encryption'    => 'SMTP turvalisus',
            'none'          => 'Puudub',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmaili kaust',
        'log'               => 'E-posti logid',

        'templates' => [
            'subject'                   => 'Pealkiri',
            'body'                      => 'Sisu',
            'tags'                      => '<strong>Saadaolevad sildid:</strong> :tag_list',
            'invoice_new_customer'      => 'Uus Arve Šabloon (saada kliendile)',
            'invoice_remind_customer'   => 'Arve Meeldetuletuse Šabloon (saada kliendile)',
            'invoice_remind_admin'      => 'Arve Meeldetuletuse Šabloon (saada adminile)',
            'invoice_recur_customer'    => 'Korduvarve Šabloon (saada kliendile)',
            'invoice_recur_admin'       => 'Korduvarve Šabloon (saada adminile)',
            'invoice_payment_customer'  => 'Laekunud Makse Šabloon (saada kliendile)',
            'invoice_payment_admin'     => 'Laekunud Makse Šabloon (saada adminile)',
            'bill_remind_admin'         => 'Ostuarve meeldetuletus šabloon (saada adminile)',
            'bill_recur_admin'          => 'Ostuarve meeldetuletus šabloon (saada adminile)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Ajastamine',
        'description'       => 'Automaatsed meeldetuletused ja korduv käsk',
        'send_invoice'      => 'Saada arve meeldetuletus',
        'invoice_days'      => 'Saada, kui tähtaeg on möödas',
        'send_bill'         => 'Saada ostuarve meeldetuletus',
        'bill_days'         => 'Saada enne ostu maksetähtaega',
        'cron_command'      => 'Ajastatud töö käsklus',
        'schedule_time'     => 'Mis tunnil käivitatakse',
    ],

    'categories' => [
        'description'       => 'Piiramatu kategooria tulude, kulude ja esemete jaoks',
    ],

    'currencies' => [
        'description'       => 'Looge ja hallake valuutasid ning määrake nende kursid',
    ],

    'taxes' => [
        'description'       => 'Fikseeritud, tavaline, kaasav ja liitmaksumäär',
    ],

];
