<?php

return [

    'bill_number'           => 'Numarul facturii',
    'bill_date'             => 'Data facturii',
    'total_price'           => 'Preț total',
    'due_date'              => 'Scadenta',
    'order_number'          => 'Număr de comandă',
    'bill_from'             => 'Factura de la',

    'quantity'              => 'Cantitate',
    'price'                 => 'Preț',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Reducere',
    'tax_total'             => 'Total taxe',
    'total'                 => 'Total',

    'item_name'             => 'Articol|Articole
Nume articol|Nume articole',

    'show_discount'         => ':discount% Reducere',
    'add_discount'          => 'Adauga Reducere',
    'discount_desc'         => 'din subtotal',

    'payment_due'           => 'De plată',
    'amount_due'            => 'Suma de plata',
    'paid'                  => 'Plătit',
    'histories'             => 'Istoric',
    'payments'              => 'Plăți',
    'add_payment'           => 'Adauga plata',
    'mark_received'         => 'Marcheaza ca Primit/a',
    'download_pdf'          => 'Descarca PDF',
    'send_mail'             => 'Trimite Email',
    'create_bill'           => 'Creați factură',
    'receive_bill'          => 'Receive Bill',
    'make_payment'          => 'Plătește',

    'status' => [
        'draft'             => 'Ciornă',
        'received'          => 'Primit
Primite',
        'partial'           => 'Parţial
Parţială',
        'paid'              => 'Plătit',
    ],

    'messages' => [
        'received'          => 'Factura marcata ca si Primita!',
        'draft'             => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'       => 'Creat pe data de :date',
            'receive' => [
                'draft'     => 'Not sent',
                'received'  => 'Primit pe data de :date',
            ],
            'paid' => [
                'await'     => 'În aşteptarea plăţii',
            ],
        ],
    ],

];
