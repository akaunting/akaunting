<?php

return [

    'company' => [
        'description'       => 'Pakeisti kompanijos pavadinimą, el. paštą, adresą ir t.t.',
        'name'              => 'Pavadinimas',
        'email'             => 'El. paštas',
        'phone'             => 'Telefonas',
        'address'           => 'Adresas',
        'logo'              => 'Logotipas',
    ],

    'localisation' => [
        'description'       => 'Nustatyti biudžetinius metus, laiko juostas, datos formatą ir kitus lokalizacijos nustatymus.',
        'financial_start'   => 'Finansinių metų pradžia',
        'timezone'          => 'Laiko juosta',
        'date' => [
            'format'        => 'Datos formatas',
            'separator'     => 'Datos skirtukas',
            'dash'          => 'Brūkšnelis (-)',
            'dot'           => 'Taškas (.)',
            'comma'         => 'Kablelis (,)',
            'slash'         => 'Pasvirasis brūkšnys (/)',
            'space'         => 'Tarpas ( )',
        ],
        'percent' => [
            'title'         => 'Procentų (%) Pozicija',
            'before'        => 'Prieš skaičių',
            'after'         => 'Po skaičiaus',
        ],
        'discount_location' => [
            'name'          => 'Nuolaidos vieta',
            'item'          => 'Eilutėje',
            'total'         => 'Iš viso',
            'both'          => 'Both line and total',
        ],
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'Sąskaitos serija',
        'digit'             => 'Skaitmenų kiekis',
        'next'              => 'Kitas numeris',
        'logo'              => 'Logotipas',
        'custom'            => 'Pasirinktinis',
        'item_name'         => 'Elemento pavadinimas',
        'item'              => 'Elementai',
        'product'           => 'Produktai',
        'service'           => 'Paslaugos',
        'price_name'        => 'Kainos pavadinimas',
        'price'             => 'Kaina',
        'rate'              => 'Kursas',
        'quantity_name'     => 'Kiekio pavadinimas',
        'quantity'          => 'Kiekis',
        'payment_terms'     => 'Mokėjimo Sąlygos',
        'title'             => 'Pavadinimas',
        'subheading'        => 'Poraštė',
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
        'choose_template'   => 'Pasirinkite sąskaitos-faktūros šabloną.',
        'default'           => 'Numatytas',
        'classic'           => 'Klasikinis',
        'modern'            => 'Modernus',
    ],

    'default' => [
        'description'       => 'Numatytoji sąskaita, valuta, kalba',
        'list_limit'        => 'Įrašų puslapyje',
        'use_gravatar'      => 'Naudoti Gravatar',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
        'protocol'          => 'Protokolas',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP adresas',
            'port'          => 'SMTP portas',
            'username'      => 'SMTP prisijungimo vardas',
            'password'      => 'SMTP slaptažodis',
            'encryption'    => 'SMTP saugumas',
            'none'          => 'Joks',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail kelias',
        'log'               => 'Prisijungti el. Paštu',

        'templates' => [
            'subject'                   => 'Tema',
            'body'                      => 'Tekstas',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
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
        'name'              => 'Planavimas',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'Siųsti SF priminimą',
        'invoice_days'      => 'Siųsti pavėlavus',
        'send_bill'         => 'Siųsti sąskaitos priminimą',
        'bill_days'         => 'Siųsti prieš pavėlavimą',
        'cron_command'      => 'Cron komanda',
        'schedule_time'     => 'Paleidimo valanda',
    ],

    'categories' => [
        'description'       => 'Unlimited categories for income, expense, and item',
    ],

    'currencies' => [
        'description'       => 'Create and manage currencies and set their rates',
    ],

    'taxes' => [
        'description'       => 'Fixed, normal, inclusive, and compound tax rates',
    ],

];
