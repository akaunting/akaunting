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
    'recurring_bills'       => 'Tilbagevendende Regninger|Tilbagevendende Regninger',

    'show_discount'         => ':discount% rabat',
    'add_discount'          => 'Tilføj rabat',
    'discount_desc'         => 'af subtotal',

    'payment_made'          => 'Betaling fortaget',
    'payment_due'           => 'Betalingsfrist',
    'amount_due'            => 'Forfaldent beløb',
    'paid'                  => 'Betalt',
    'histories'             => 'Historik',
    'payments'              => 'Betalinger',
    'add_payment'           => 'Tilføj betaling',
    'mark_paid'             => 'Marker som betalt',
    'mark_received'         => 'Marker som modtaget',
    'mark_cancelled'        => 'Marker som annulleret',
    'download_pdf'          => 'Download PDF',
    'send_mail'             => 'Send e-mail',
    'create_bill'           => 'Opret faktura',
    'receive_bill'          => 'Modtag faktura',
    'make_payment'          => 'Opret betaling',

    'form_description' => [
        'billing'           => 'Faktureringsoplysninger vises i din regning. Regningsdato bruges i skrivebordet og rapporter. Vælg den dato, du forventer at betale som forfaldsdato.',
    ],

    'messages' => [
        'draft'             => 'Dette er et <b>UDKAST</b> til en regning og vil først blive vist i oversigten, når den er markeret som modtaget.',

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
