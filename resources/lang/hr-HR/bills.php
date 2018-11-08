<?php

return [

    'bill_number'       => 'Broj računa',
    'bill_date'         => 'Datum računa',
    'total_price'       => 'Ukupna cijena',
    'due_date'          => 'Datum dospijeća',
    'order_number'      => 'Broj narudžbe',
    'bill_from'         => 'Račun od',

    'quantity'          => 'Količina',
    'price'             => 'Cijena',
    'sub_total'         => 'Podzbroj',
    'discount'          => 'Popust',
    'tax_total'         => 'Porez Ukupno',
    'total'             => 'Ukupno',

    'item_name'         => 'Naziv stavke|Nazivi stavaka',

    'show_discount'     => ':discount% popusta',
    'add_discount'      => 'Dodaj popust',
    'discount_desc'     => 'od podzbroja',

    'payment_due'       => 'Dospijeća plaćanja',
    'amount_due'        => 'Dospjeli iznos',
    'paid'              => 'Plaćeno',
    'histories'         => 'Povijesti',
    'payments'          => 'Plaćanja',
    'add_payment'       => 'Dodaj plaćanje',
    'mark_received'     => 'Označi kao primljeno',
    'download_pdf'      => 'Preuzmite PDF',
    'send_mail'         => 'Pošalji e-mail',

    'status' => [
        'draft'         => 'Skica',
        'received'      => 'Primljeno',
        'partial'       => 'Djelomično',
        'paid'          => 'Plaćeno',
    ],

    'messages' => [
        'received'      => 'Račun označen kao uspješno primljen!',
        'draft'          => 'Ovo je <b>SKICA</b> računa i odrazit će se na grafikone nakon što se zaprimi.',

        'status' => [
            'created'   => 'Kreirano :date',
            'receive'      => [
                'draft'     => 'Nije poslano',
                'received'  => 'Zaprimljeno :date',
            ],
            'paid'      => [
                'await'     => 'Čeka plaćanje',
            ],
        ],
    ],

];
