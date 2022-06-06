<?php

return [

    'bill_number'           => 'Broj računa',
    'bill_date'             => 'Datum računa',
    'bill_amount'           => 'Iznos računa',
    'total_price'           => 'Ukupna cijena',
    'due_date'              => 'Datum dospijeća',
    'order_number'          => 'Broj narudžbe',
    'bill_from'             => 'Račun od',

    'quantity'              => 'Količina',
    'price'                 => 'Cijena',
    'sub_total'             => 'Međuzbir',
    'discount'              => 'Popust',
    'item_discount'         => 'Popust',
    'tax_total'             => 'Porez Ukupno',
    'total'                 => 'Ukupno',

    'item_name'             => 'Naziv stavke|Nazivi stavaka',
    'recurring_bills'       => 'Ponavljajući račun|Ponavljajući računi',

    'show_discount'         => ':discount% popusta',
    'add_discount'          => 'Dodaj popust',
    'discount_desc'         => 'od podzbroja',

    'payment_made'          => 'Dospijeća plaćanja',
    'payment_due'           => 'Dospijeća plaćanja',
    'amount_due'            => 'Dospjeli iznos',
    'paid'                  => 'Plaćeno',
    'histories'             => 'Povijesti',
    'payments'              => 'Plaćanja',
    'add_payment'           => 'Dodaj plaćanje',
    'mark_paid'             => 'Označi kao plaćeno',
    'mark_received'         => 'Označi kao primljeno',
    'mark_cancelled'        => 'Označi kao plaćeno',
    'download_pdf'          => 'Preuzmite PDF',
    'send_mail'             => 'Pošalji e-mail',
    'create_bill'           => 'Kreiraj fakturu',
    'receive_bill'          => 'Primiti račun',
    'make_payment'          => 'Kreiraj uplatu',

    'form_description' => [
        'billing'           => 'Detalji naplate se pojavljuju na vašem računu. Datum računa se koristi na kontrolnoj tabli i izvještajima. Odaberite datum koji očekujete da platite kao datum dospijeća.',
    ],

    'messages' => [
        'draft'             => 'Ovo je <b>SKICA</b> računa i odrazit će se na grafikone nakon što se zaprimi.',

        'status' => [
            'created'       => 'Kreirano :date',
            'receive' => [
                'draft'     => 'Nije poslano',
                'received'  => 'Zaprimljeno :date',
            ],
            'paid' => [
                'await'     => 'Čeka plaćanje',
            ],
        ],
    ],

];
