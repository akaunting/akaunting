<?php

return [

    'invoice_number'        => 'Reikningur númer',
    'invoice_date'          => 'Dagsetning reiknings',
    'total_price'           => 'Heildarverð',
    'due_date'              => 'Eindagi',
    'order_number'          => 'Pöntunarnúmer',
    'bill_to'               => 'Greiðandi',

    'quantity'              => 'Magn',
    'price'                 => 'Verð',
    'sub_total'             => 'Samtals',
    'discount'              => 'Afsláttur',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'VSK',
    'total'                 => 'Samtals',

    'item_name'             => 'Lýsing|Lýsingar',

    'show_discount'         => ':discount% afsláttur',
    'add_discount'          => 'Setja afslátt',
    'discount_desc'         => 'af samtölu',

    'payment_due'           => 'Eindagi',
    'paid'                  => 'Greitt',
    'histories'             => 'Yfirlit',
    'payments'              => 'Greiðslur',
    'add_payment'           => 'Bæta við greiðslu',
    'mark_paid'             => 'Merja greitt',
    'mark_sent'             => 'Merkja sent',
    'mark_viewed'           => 'Mark Viewed',
    'mark_cancelled'        => 'Mark Cancelled',
    'download_pdf'          => 'Niðurhala PDF',
    'send_mail'             => 'Senda tölvupóst',
    'all_invoices'          => 'Skráðu þig inn til að sjá alla reikninga',
    'create_invoice'        => 'Búa til reikning',
    'send_invoice'          => 'Senda reikning',
    'get_paid'              => 'Fá greitt',
    'accept_payments'       => 'Taka á moti netgreiðslu',

    'statuses' => [
        'draft'             => 'Draft',
        'sent'              => 'Sent',
        'viewed'            => 'Viewed',
        'approved'          => 'Approved',
        'partial'           => 'Partial',
        'paid'              => 'Paid',
        'overdue'           => 'Overdue',
        'unpaid'            => 'Unpaid',
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent!',
        'marked_sent'       => 'Invoice marked as sent!',
        'marked_paid'       => 'Invoice marked as paid!',
        'marked_viewed'     => 'Invoice marked as viewed!',
        'marked_cancelled'  => 'Invoice marked as cancelled!',
        'email_required'    => 'Engin tölvupóstur fyrir þennan viðskiptavin!',
        'draft'             => 'Þetta er <b>PRUFU</b> reikningur sem sést á kortum eftir að hann er móttekinn.',

        'status' => [
            'created'       => 'Búinn til :date',
            'viewed'        => 'Viewed',
            'send' => [
                'draft'     => 'Ekki sendur',
                'sent'      => 'Sendur :date',
            ],
            'paid' => [
                'await'     => 'Bíður greiðslu',
            ],
        ],
    ],

];
