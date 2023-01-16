<?php

return [

    'invoice_number'        => 'Arve number',
    'invoice_date'          => 'Arve kuupäev',
    'total_price'           => 'Hind kokku',
    'due_date'              => 'Tähtaeg',
    'order_number'          => 'Tellimuse number',
    'bill_to'               => 'Ostuarve saaja',

    'quantity'              => 'Kogus',
    'price'                 => 'Hind',
    'sub_total'             => 'Vahesumma',
    'discount'              => 'Allahindlus',
    'item_discount'         => 'Rea soodustus',
    'tax_total'             => 'Maksud kokku',
    'total'                 => 'Kokku',

    'item_name'             => 'Kauba nimi|Kauba nimed',

    'show_discount'         => ':discount% allahindlus',
    'add_discount'          => 'Lisa allahindlus',
    'discount_desc'         => 'vahesummast',

    'payment_due'           => 'Makse tähtaeg',
    'paid'                  => 'Makstud',
    'histories'             => 'Ajalugu',
    'payments'              => 'Maksed',
    'add_payment'           => 'Lisa makse',
    'mark_paid'             => 'Märgi makstuks',
    'mark_sent'             => 'Märgi saadetuks',
    'mark_viewed'           => 'Märgi vaadatuks',
    'mark_cancelled'        => 'Märgi tühistatuks',
    'download_pdf'          => 'Laadi alla PDF',
    'send_mail'             => 'Saada e-kiri',
    'all_invoices'          => 'Logi sisse, et vaadata kõiki arveid',
    'create_invoice'        => 'Koosta arve',
    'send_invoice'          => 'Saada arve',
    'get_paid'              => 'Väljamakse',
    'accept_payments'       => 'Aktsepteeri veebimakseid',

    'messages' => [
        'email_required'    => 'Selle kliendi e-posti aadressi pole!',
        'draft'             => 'See on arve <b>MUSTAND</b> ja see kajastub diagrammides pärast saatmist.',

        'status' => [
            'created'       => 'Loodud :date',
            'viewed'        => 'Vaadatud',
            'send' => [
                'draft'     => 'Saatmata',
                'sent'      => 'Saadetud :date',
            ],
            'paid' => [
                'await'     => 'Ootab tasumist',
            ],
        ],
    ],

];
