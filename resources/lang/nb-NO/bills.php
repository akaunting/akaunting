<?php

return [

    'bill_number'           => 'Fakturanummer',
    'bill_date'             => 'Fakturadato',
    'total_price'           => 'Totalpris',
    'due_date'              => 'Forfallsdato',
    'order_number'          => 'Ordrenummer',
    'bill_from'             => 'Faktura fra',

    'quantity'              => 'Antall',
    'price'                 => 'Pris',
    'sub_total'             => 'Sum',
    'discount'              => 'Rabatt',
    'tax_total'             => 'Mva',
    'total'                 => 'Totalt',

    'item_name'             => 'Artikkelnavn | Artikkelnavn',

    'show_discount'         => ':discount% rabatt',
    'add_discount'          => 'Legg til rabatt',
    'discount_desc'         => 'av delsum',

    'payment_due'           => 'Forfallsdato',
    'amount_due'            => 'Forfallsbeløp',
    'paid'                  => 'Betalt',
    'histories'             => 'Historikk',
    'payments'              => 'Utbetalinger',
    'add_payment'           => 'Legg til betaling',
    'mark_received'         => 'Merk som mottatt',
    'download_pdf'          => 'Last ned PDF',
    'send_mail'             => 'Send e-post',
    'create_bill'           => 'Opprett faktura',
    'receive_bill'          => 'Motta faktura',
    'make_payment'          => 'Opprett betaling',

    'status' => [
        'draft'             => 'Utkast',
        'received'          => 'Mottatt',
        'partial'           => 'Delvis',
        'paid'              => 'Betalt',
    ],

    'messages' => [
        'received'          => 'Faktura ble merket som mottatt.',
        'draft'             => 'Dette er en <b>KLADD</b> for fakturaen som vil bli oppdatert etter at den er mottatt.',

        'status' => [
            'created'       => 'Opprettet :date',
            'receive' => [
                'draft'     => 'Ikke sendt',
                'received'  => 'Mottatt :date',
            ],
            'paid' => [
                'await'     => 'Avventer betaling',
            ],
        ],
    ],

];
