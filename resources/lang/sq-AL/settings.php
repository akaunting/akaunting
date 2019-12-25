<?php

return [

    'company' => [
        'description'       => 'Ndryshoni emrin e kompanisë, emailin, adresën, numrin e taksave etj',
        'name'              => 'Emri',
        'email'             => 'Email',
        'phone'             => 'Telefoni',
        'address'           => 'Adresa',
        'logo'              => 'Logoja',
    ],

    'localisation' => [
        'description'       => 'Vendosni vitin fiskal, zonën e kohës, formatin e datës dhe më shumë vendorë',
        'financial_start'   => 'Fillimi i Vitit Financiar',
        'timezone'          => 'Zona Kohore',
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
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
    ],

    'default' => [
        'description'       => 'Llogaria, monedha, gjuha e paracaktuar e kompanisë suaj',
        'list_limit'        => 'Rekordet Për Faqe',
        'use_gravatar'      => 'Përdorni Gravatar',
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
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_payment_customer'  => 'Payment Received Template (sent to customer)',
            'invoice_payment_admin'     => 'Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planifikimi',
        'description'       => 'Automatic reminders and command for recurring',
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
