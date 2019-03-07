<?php

return [

    'invoice_number'        => 'Faktura nummer',
    'invoice_date'          => 'Faktura dato',
    'total_price'           => 'Total pris',
    'due_date'              => 'Forfaldsdato',
    'order_number'          => 'Ordrenummer',
    'bill_to'               => 'Faktura til',

    'quantity'              => 'Antal',
    'price'                 => 'Pris',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Rabat',
    'tax_total'             => 'Moms i alt',
    'total'                 => 'I alt',

    'item_name'             => 'Vare navn|Vare navne',

    'show_discount'         => ':discount% Rabat',
    'add_discount'          => 'Tilføj rabat',
    'discount_desc'         => 'subtotal',

    'payment_due'           => 'Betalingsfrist',
    'paid'                  => 'Betalt',
    'histories'             => 'Historik',
    'payments'              => 'Betalinger',
    'add_payment'           => 'Tilføj betaling',
    'mark_paid'             => 'Marker som betalt',
    'mark_sent'             => 'Marker som sendt',
    'download_pdf'          => 'Download som PDF',
    'send_mail'             => 'Send E-mail',
    'all_invoices'          => 'Log ind for at se alle fakturaer',
    'create_invoice'        => 'Opret faktura',
    'send_invoice'          => 'Send faktura',
    'get_paid'              => 'Betal',
    'accept_payments'       => 'Accepter onlinebetalinger',

    'status' => [
        'draft'             => 'Kladde',
        'sent'              => 'Sendt',
        'viewed'            => 'Vist',
        'approved'          => 'Godkendt',
        'partial'           => 'Delvist',
        'paid'              => 'Betalt',
    ],

    'messages' => [
        'email_sent'        => 'Faktura sendt over E-mail!',
        'marked_sent'       => 'Faktura markeret som sendt!',
        'email_required'    => 'Ingen E-mail-adresse for kunden!',
        'draft'             => 'Dette er et <b>UDKAST</b> til faktura og vil blive vist som diagrammer, når det bliver sendt.',

        'status' => [
            'created'       => 'Oprettet den :date',
            'send' => [
                'draft'     => 'Ikke sendt',
                'sent'      => 'Sendt den :date',
            ],
            'paid' => [
                'await'     => 'Afventer betaling',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Du modtager denne E-mail, fordi du har en faktura på :amount til :customer kunde.',
        'button'            => 'Betal nu',
    ],

];
