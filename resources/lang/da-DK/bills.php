<?php

return [

    'bill_number'           => 'Fakturanummer',
    'bill_date'             => 'Fakturadato',
    'bill_amount'           => 'Regningens beløb',
    'total_price'           => 'Total pris',
    'due_date'              => 'Forfaldsdato',
    'order_number'          => 'Ordrenummer',
    'bill_from'             => 'Faktura fra',

    'quantity'              => 'Antal',
    'price'                 => 'Pris',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Rabat',
    'item_discount'         => 'Linjerabat',
    'tax_total'             => 'Moms i alt',
    'total'                 => 'I alt',

    'item_name'             => 'Varenavn|Varenavne',

    'show_discount'         => ':discount% rabat',
    'add_discount'          => 'Tilføj rabat',
    'discount_desc'         => 'af subtotal',

    'payment_due'           => 'Betalingsfrist',
    'amount_due'            => 'Forfaldent beløb',
    'paid'                  => 'Betalt',
    'histories'             => 'Historik',
    'payments'              => 'Betalinger',
    'add_payment'           => 'Tilføj betaling',
    'mark_paid'             => 'Marker som betalt',
    'mark_received'         => 'Modtagelse godkendt',
    'mark_cancelled'        => 'Marker som annulleret',
    'download_pdf'          => 'Download PDF',
    'send_mail'             => 'Send e-mail',
    'create_bill'           => 'Opret faktura',
    'receive_bill'          => 'Modtag faktura',
    'make_payment'          => 'Opret betaling',

    'messages' => [
        'draft'             => 'Dette er et <b>UDKAST</b> til faktura og vil først blive vist i oversigten, når den er afsendt.',

        'status' => [
            'created'       => 'Oprettet den :date',
            'receive' => [
                'draft'     => 'Ikke modtaget',
                'received'  => 'Modtaget den :date',
            ],
            'paid' => [
                'await'     => 'Afventer betaling',
            ],
        ],
    ],

];
