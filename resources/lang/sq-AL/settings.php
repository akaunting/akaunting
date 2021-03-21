<?php

return [

    'company' => [
        'description'                => 'Ndryshoni emrin e kompanisë, emailin, adresën, numrin e taksave etj',
        'name'                       => 'Emri',
        'email'                      => 'Email',
        'phone'                      => 'Telefoni',
        'address'                    => 'Adresa',
        'edit_your_business_address' => 'Redakto adresën e biznesit tënd',
        'logo'                       => 'Logoja',
    ],

    'localisation' => [
        'description'       => 'Vendosni vitin fiskal, zonën e kohës, formatin e datës dhe më shumë vendorë',
        'financial_start'   => 'Fillimi i Vitit Financiar',
        'timezone'          => 'Zona Kohore',
        'financial_denote' => [
            'title'         => 'Treguesi i Vitit Fiskal',
            'begins'        => 'Deri në vitin në të cilin fillon',
            'ends'          => 'Deri në vitin në të cilin përfundon',
        ],
        'date' => [
            'format'        => 'Formati i Datës',
            'separator'     => 'Ndarës i Datës',
            'dash'          => 'Vizë (-)',
            'dot'           => 'Pikë (.)',
            'comma'         => 'Presje (,)',
            'slash'         => 'Prerje (/)',
            'space'         => 'Hapësirë ( )',
        ],
        'percent' => [
            'title'         => 'Pozicioni Përqindja (%)',
            'before'        => 'Para Numrit',
            'after'         => 'Pas Numrit',
        ],
        'discount_location' => [
            'name'          => 'Vendndodhja e Zbritjes',
            'item'          => 'Në rresht',
            'total'         => 'Në total',
            'both'          => 'Në rresht dhe në total',
        ],
    ],

    'invoice' => [
        'description'       => 'Rregulloni parashtesën e faturës, numrin, termat, footer etj',
        'prefix'            => 'Parashtesa e numrit',
        'digit'             => 'Gjatësia a numrit',
        'next'              => 'Numri tjetër',
        'logo'              => 'Logoja',
        'custom'            => 'Special',
        'item_name'         => 'Emri i artikullit',
        'item'              => 'Artikujt',
        'product'           => 'Produktet',
        'service'           => 'Shërbimet',
        'price_name'        => 'Emri i çmimit',
        'price'             => 'Çmimi',
        'rate'              => 'Normë',
        'quantity_name'     => 'Emri i sasisë',
        'quantity'          => 'Sasia',
        'payment_terms'     => 'Kushtet e Pagesës',
        'title'             => 'Titulli',
        'subheading'        => 'Nëntitull',
        'due_receipt'       => 'Me rastin e marrjes',
        'due_days'          => 'Afati brenda :days ditëve',
        'choose_template'   => 'Zgjidhni modelin e faturës',
        'default'           => 'Parazgjedhur',
        'classic'           => 'Klasike',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Fshih Emrin e Artikullit',
            'item_description'  => 'Fshih Përshkrimin e Artikullit',
            'quantity'          => 'Fshih Sasinë',
            'price'             => 'Fshih Çmimin',
            'amount'            => 'Fshih Shumën',
        ],
    ],

    'default' => [
        'description'       => 'Llogaria, monedha, gjuha e paracaktuar e kompanisë suaj',
        'list_limit'        => 'Rekordet Për Faqe',
        'use_gravatar'      => 'Përdorni Gravatar',
        'income_category'   => 'Kategoria e Fitimeve',
        'expense_category'  => 'Kategoria e Shpenzimeve',
    ],

    'email' => [
        'description'       => 'Ndryshoni modelet e protokollit dërgues dhe modelet e postës elektronike',
        'protocol'          => 'Protokolli',
        'php'               => 'PHP Email',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'Emri i Përdorimit SMTP',
            'password'      => 'Fjalëkalimi i SMTP',
            'encryption'    => 'Siguria SMTP',
            'none'          => 'Asnjë',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Logo Emailet',

        'templates' => [
            'subject'                   => 'Subjekti',
            'body'                      => 'Trupi',
            'tags'                      => '<strong>Etiketa të Disponueshme:</strong> :tag_list',
            'invoice_new_customer'      => 'Modeli i Ri i Faturave (dërguar klientit)',
            'invoice_remind_customer'   => 'Modeli i Kujtesës së Faturës (dërguar klientit)',
            'invoice_remind_admin'      => 'Modeli i Kujtesës së Faturës (dërguar administratorit)',
            'invoice_recur_customer'    => 'Modeli i Përsëritur i Faturës (dërguar klientit)',
            'invoice_recur_admin'       => 'Modeli i Përsëritur i Faturës (dërguar administratorit)',
            'invoice_payment_customer'  => 'Modeli i Marrjes së Pagesave (dërguar klientit)',
            'invoice_payment_admin'     => 'Modeli i Marrjes së Pagesave (dërguar administratorit)',
            'bill_remind_admin'         => 'Modeli i Kujtesës së Faturës (dërguar administratorit)',
            'bill_recur_admin'          => 'Modeli i Përsëritur i Faturave (dërguar administratorit)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planifikimi',
        'description'       => 'Kujtime automatike dhe komanda për përsëritje',
        'send_invoice'      => 'Dërgo Faturën Rikujtimor',
        'invoice_days'      => 'Dërgo Pas Ditëve të Duhura',
        'send_bill'         => 'Dërgo Faturën Rikujtimor',
        'bill_days'         => 'Dërgo Para Ditëve të Duhura',
        'cron_command'      => 'Komanda Cron',
        'schedule_time'     => 'Ora për të Kandiduar',
    ],

    'categories' => [
        'description'       => 'Kategoritë e pakufizuara për të ardhurat, shpenzimet dhe sendet',
    ],

    'currencies' => [
        'description'       => 'Krijoni dhe menaxhoni monedhat dhe vendosni normat e tyre',
    ],

    'taxes' => [
        'description'       => 'Normat e taksave fikse, normale, gjithëpërfshirëse dhe komplekse',
    ],

];
