<?php

return [

    'bill_number'           => 'Arve number',
    'bill_date'             => 'Arve kuupäev',
    'total_price'           => 'Koguhind',
    'due_date'              => 'Tähtaeg',
    'order_number'          => 'Tellimuse number',
    'bill_from'             => 'Arve saatja',

    'quantity'              => 'Kogus',
    'price'                 => 'Hind',
    'sub_total'             => 'Vahesumma',
    'discount'              => 'Allahindlus',
    'item_discount'         => 'Rea soodustus',
    'tax_total'             => 'Maksud kokku',
    'total'                 => 'Kokku',

    'item_name'             => 'Kauba nimi | Kauba nimed',

    'show_discount'         => 'Soodustus :discount%',
    'add_discount'          => 'Lisa allahindlus',
    'discount_desc'         => 'vahesummast',

    'payment_due'           => 'Makse tähtaeg',
    'amount_due'            => 'Tasumisele kuuluv summa',
    'paid'                  => 'Makstud',
    'histories'             => 'Ajalood',
    'payments'              => 'Maksed',
    'add_payment'           => 'Lisa makse',
    'mark_paid'             => 'Märgi makstuks',
    'mark_received'         => 'Makse on saabunud',
    'mark_cancelled'        => 'Märgi tühistatuks',
    'download_pdf'          => 'Laadi alla PDF',
    'send_mail'             => 'Saada kiri',
    'create_bill'           => 'Loo arve',
    'receive_bill'          => 'Arve vastuvõtmine',
    'make_payment'          => 'Tee makse',

    'statuses' => [
        'draft'             => 'Mustand',
        'received'          => 'Vastuvõetud',
        'partial'           => 'Osaline',
        'paid'              => 'Makstud',
        'overdue'           => 'Tähtaja ületanud',
        'unpaid'            => 'Maksmata',
        'cancelled'         => 'Tühistatud',
    ],

    'messages' => [
        'marked_received'   => 'Arve on märgitud kättesaaduks!',
        'marked_paid'       => 'Arve on märgitud makstuks!',
        'marked_cancelled'  => 'Arve on märgitud tühistatuks!',
        'draft'             => 'See on <b> MUSTAND </b> arve ja kajastub graafikutes pärast selle vastuvõtmist.',

        'status' => [
            'created'       => 'Loodud :date',
            'receive' => [
                'draft'     => 'Saatmata',
                'received'  => 'Saadud :date',
            ],
            'paid' => [
                'await'     => 'Ootab tasumist',
            ],
        ],
    ],

];
