<?php

return [

    'bill_number'           => 'Laskunumero',
    'bill_date'             => 'Laskun päiväys',
    'total_price'           => 'Kokonaishinta',
    'due_date'              => 'Eräpäivä',
    'order_number'          => 'Tilausnumero',
    'bill_from'             => 'Saaja',

    'quantity'              => 'Määrä',
    'price'                 => 'Hinta',
    'sub_total'             => 'Välisumma',
    'discount'              => 'Alennus',
    'item_discount'         => 'Rivialennus',
    'tax_total'             => 'Vero yhteensä',
    'total'                 => 'Yhteensä',

    'item_name'             => 'Tuotteen nimi|Tuotteiden nimet',

    'show_discount'         => ':discount% alennus',
    'add_discount'          => 'Lisää alennus',
    'discount_desc'         => 'välisummasta',

    'payment_due'           => 'Maksu erääntyy',
    'amount_due'            => 'Maksettava summa',
    'paid'                  => 'Maksettu',
    'histories'             => 'Historia',
    'payments'              => 'Maksut',
    'add_payment'           => 'Lisää maksu',
    'mark_paid'             => 'Merkitse maksetuksi',
    'mark_received'         => 'Merkitse vastaanotetuksi',
    'mark_cancelled'        => 'Merkitse peruutetuksi',
    'download_pdf'          => 'Lataa PDF',
    'send_mail'             => 'Lähetä sähköposti',
    'create_bill'           => 'Luo lasku',
    'receive_bill'          => 'Vastaanota lasku',
    'make_payment'          => 'Tee maksu',

    'messages' => [
        'draft'             => 'Tämä lasku on <b>LUONNOS</b> ja se sisällytetään kaavioihin, kun se on vastaanotettu.',

        'status' => [
            'created'       => 'Luotu :date',
            'receive' => [
                'draft'     => 'Ei lähetetty',
                'received'  => 'Vastaanotettu :date',
            ],
            'paid' => [
                'await'     => 'Odottaa maksua',
            ],
        ],
    ],

];
