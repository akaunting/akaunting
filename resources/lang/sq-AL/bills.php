<?php

return [

    'bill_number'           => 'Numri i Faturës',
    'bill_date'             => 'Data e Faturës',
    'total_price'           => 'Cmimi Total',
    'due_date'              => 'Data e duhur',
    'order_number'          => 'Numri i Porosisë',
    'bill_from'             => 'Fature Nga',

    'quantity'              => 'Sasia',
    'price'                 => 'Çmimi',
    'sub_total'             => 'Nëntotali',
    'discount'              => 'Skonto',
    'tax_total'             => 'Tatimi Gjithsej',
    'total'                 => 'Totali',

    'item_name'             => 'Emri i Artikullit | Emrat e Artikullit',

    'show_discount'         => ':discount% Skonto',
    'add_discount'          => 'Shto Skonto',
    'discount_desc'         => 'e nëntotalit',

    'payment_due'           => 'Pagesa e Duhur',
    'amount_due'            => 'Shuma e Duhur',
    'paid'                  => 'I paguar',
    'histories'             => 'Historitë',
    'payments'              => 'Pagesat',
    'add_payment'           => 'Shto Pagesë',
    'mark_received'         => 'Shënoje të Marrë',
    'download_pdf'          => 'Shkarko PDF',
    'send_mail'             => 'Dërgo Email',
    'create_bill'           => 'Krijo Faturë',
    'receive_bill'          => 'Merre Faturën',
    'make_payment'          => 'Bëj Pagesën',

    'status' => [
        'draft'             => 'Draft',
        'received'          => 'Marrë',
        'partial'           => 'I pjesshëm',
        'paid'              => 'I paguar',
    ],

    'messages' => [
        'received'          => 'Fatura shënohet si i marrë me sukses!',
        'draft'             => 'Ky është një faturë <b>DRAFT</b> dhe do të pasqyrohet në skema pas marrjes së tij.',

        'status' => [
            'created'       => 'Krijuar më :date',
            'receive' => [
                'draft'     => 'Nuk është dërguar',
                'received'  => 'Pranuar më :date',
            ],
            'paid' => [
                'await'     => 'Duke pritur pagesen',
            ],
        ],
    ],

];
