<?php

return [

    'bill_number'           => 'Reikningsnúmer',
    'bill_date'             => 'Dagsetning',
    'total_price'           => 'Heildarverð',
    'due_date'              => 'Eindagi',
    'order_number'          => 'Pöntunarnúmer',
    'bill_from'             => 'Reikningur frá',

    'quantity'              => 'Magn',
    'price'                 => 'Verð',
    'sub_total'             => 'Samtals',
    'discount'              => 'Afsláttur',
    'tax_total'             => 'VSK',
    'total'                 => 'Samtals',

    'item_name'             => 'Lýsing|Lýsingar',

    'show_discount'         => ':discount% afsláttur',
    'add_discount'          => 'Setja afslátt',
    'discount_desc'         => 'af samtölu',

    'payment_due'           => 'Eindagi',
    'amount_due'            => 'Upphæð til greiðslu',
    'paid'                  => 'Greitt',
    'histories'             => 'Yfirlit',
    'payments'              => 'Greiðslur',
    'add_payment'           => 'Bæta við greiðslu',
    'mark_received'         => 'Móttekið',
    'download_pdf'          => 'Niðurhala PDF',
    'send_mail'             => 'Senda tölvupóst',
    'create_bill'           => 'Skapa reikning',
    'receive_bill'          => 'Taka á móti reikningi',
    'make_payment'          => 'Greiða',

    'statuses' => [
        'draft'             => 'Uppkast',
        'received'          => 'Móttekið',
        'partial'           => 'Hluta',
        'paid'              => 'Greitt',
    ],

    'messages' => [
        'received'          => 'Reikningur merktur sem móttekinn!',
        'draft'             => 'Þetta er <b>PRUFU</b> reikningur sem sést á kortum eftir að hann er móttekinn.',

        'status' => [
            'created'       => 'Búinn til :date',
            'receive' => [
                'draft'     => 'Ekki sendur',
                'received'  => 'Móttekið: :date',
            ],
            'paid' => [
                'await'     => 'Bíður greiðslu',
            ],
        ],
    ],

];
