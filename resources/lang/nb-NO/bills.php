<?php

return [

    'bill_number'       => 'Fakturanummer',
    'bill_date'         => 'Fakturadato',
    'total_price'       => 'Totalpris',
    'due_date'          => 'Forfallsdato',
    'order_number'      => 'Ordrenummer',
    'bill_from'         => 'Faktura fra',

    'quantity'          => 'Antall',
    'price'             => 'Pris',
    'sub_total'         => 'Sum',
    'discount'          => 'Rabatt',
    'tax_total'         => 'Mva',
    'total'             => 'Totalt',

    'item_name'         => 'Enhetsnavn | Enhetsnavn',

    'show_discount'     => ':discount% rabatt',
    'add_discount'      => 'Legg til rabatt',
    'discount_desc'     => 'av delsum',

    'payment_due'       => 'Forfallsdato',
    'amount_due'        => 'Forfallsbeløp',
    'paid'              => 'Betalt',
    'histories'         => 'Historikk',
    'payments'          => 'Betalinger',
    'add_payment'       => 'Legg til betaling',
    'mark_received'     => 'Merk som mottatt',
    'download_pdf'      => 'Last ned PDF',
    'send_mail'         => 'Send e-post',

    'status' => [
        'draft'         => 'Utkast',
        'received'      => 'Mottatt',
        'partial'       => 'Delvis',
        'paid'          => 'Betalt',
    ],

    'messages' => [
        'received'      => 'Faktura ble merket som mottatt.',
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
