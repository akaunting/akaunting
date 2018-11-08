<?php

return [

    'invoice_number'    => 'Fakturanummer',
    'invoice_date'      => 'Fakturadato',
    'total_price'       => 'Totalpris',
    'due_date'          => 'Forfallsdato',
    'order_number'      => 'Ordrenummer',
    'bill_to'           => 'Faktura til',

    'quantity'          => 'Antall',
    'price'             => 'Pris',
    'sub_total'         => 'Sum',
    'discount'          => 'Rabatt',
    'tax_total'         => 'Totalt mva',
    'total'             => 'Totalt',

    'item_name'         => 'Enhetsnavn | Enhetsnavn',

    'show_discount'     => ':discount% rabatt',
    'add_discount'      => 'Legg til rabatt',
    'discount_desc'     => 'av delsum',

    'payment_due'       => 'Forfallsdato',
    'paid'              => 'Betalt',
    'histories'         => 'Historikk',
    'payments'          => 'Betalinger',
    'add_payment'       => 'Legg til betaling',
    'mark_paid'         => 'Merk som betalt',
    'mark_sent'         => 'Merk som sendt',
    'download_pdf'      => 'Last ned PDF',
    'send_mail'         => 'Send e-post',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Utkast',
        'sent'          => 'Sendt',
        'viewed'        => 'Åpnet',
        'approved'      => 'Godkjent',
        'partial'       => 'Delvis',
        'paid'          => 'Betalt',
    ],

    'messages' => [
        'email_sent'     => 'E-post med faktura har blitt sendt.',
        'marked_sent'    => 'Faktura merket som sendt.',
        'email_required' => 'E-postadresse må fylles inn.',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Du mottar denne e-posten fordi du har en kommende faktura, pålydende  :amount, til :costumer.',
        'button'        => 'Betal nå',
    ],

];
