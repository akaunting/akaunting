<?php

return [

    'bill_number'       => 'Rēķina numurs',
    'bill_date'         => 'Rēķina datums',
    'total_price'       => 'Kopējā summa',
    'due_date'          => 'Apmaksas termiņš',
    'order_number'      => 'Pasūtījuma numurs',
    'bill_from'         => 'Piegādātājs (pakalpojuma sniedzējs)',

    'quantity'          => 'Daudzums',
    'price'             => 'Cena',
    'sub_total'         => 'Kopā',
    'discount'          => 'Atlaide',
    'tax_total'         => 'Nodokļi kopā',
    'total'             => 'Kopā',

    'item_name'         => 'Nosaukums|Nosaukums',

    'show_discount'     => 'Atlaide :discount%',
    'add_discount'      => 'Pievienot atlaidi',
    'discount_desc'     => 'no',

    'payment_due'       => 'Kavēts rēķins',
    'amount_due'        => 'Kavēts maksājums',
    'paid'              => 'Samaksāts',
    'histories'         => 'Vēsture',
    'payments'          => 'Maksājumi',
    'add_payment'       => 'Pievienot maksājumu',
    'mark_received'     => 'Mark Received',
    'download_pdf'      => 'Lejupielādēt PDF',
    'send_mail'         => 'Sūtīt e-pastu',

    'status' => [
        'draft'         => 'Sagatave',
        'received'      => 'Apstiprināts',
        'partial'       => 'Daļējs',
        'paid'          => 'Samaksāts',
    ],

    'messages' => [
        'received'      => 'Rēķina saņemšana ir apstiprināta!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
