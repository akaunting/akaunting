<?php

return [

    'invoice_number'        => 'Fakturanummer',
    'invoice_date'          => 'Fakturadato',
    'total_price'           => 'Totalpris',
    'due_date'              => 'Forfallsdato',
    'order_number'          => 'Ordrenummer',
    'bill_to'               => 'Faktura til',

    'quantity'              => 'Antall',
    'price'                 => 'Pris',
    'sub_total'             => 'Sum',
    'discount'              => 'Rabatt',
    'tax_total'             => 'Totalt mva',
    'total'                 => 'Totalt',

    'item_name'             => 'Artikkelnavn | Artikkelnavn',

    'show_discount'         => ':discount% rabatt',
    'add_discount'          => 'Legg til rabatt',
    'discount_desc'         => 'av delsum',

    'payment_due'           => 'Forfallsdato',
    'paid'                  => 'Betalt',
    'histories'             => 'Historikk',
    'payments'              => 'Utbetalinger',
    'add_payment'           => 'Legg til betaling',
    'mark_paid'             => 'Merk som betalt',
    'mark_sent'             => 'Merk som sendt',
    'download_pdf'          => 'Last ned PDF',
    'send_mail'             => 'Send e-post',
    'all_invoices'          => 'Logg inn for å se alle fakturaer',
    'create_invoice'        => 'Opprett faktura',
    'send_invoice'          => 'Send faktura',
    'get_paid'              => 'Få betalt',
    'accept_payments'       => 'Aksepter online betalinger',

    'status' => [
        'draft'             => 'Utkast',
        'sent'              => 'Sendt',
        'viewed'            => 'Åpnet',
        'approved'          => 'Godkjent',
        'partial'           => 'Delvis',
        'paid'              => 'Betalt',
    ],

    'messages' => [
        'email_sent'        => 'E-post med faktura har blitt sendt.',
        'marked_sent'       => 'Faktura merket som sendt.',
        'email_required'    => 'E-postadresse må fylles inn.',
        'draft'             => 'Dette er en <b>KLADD</b> for fakturaen som vil bli oppdatert etter at den er sendt.',

        'status' => [
            'created'       => 'Opprettet :date',
            'send' => [
                'draft'     => 'Ikke sendt',
                'sent'      => 'Sendt :date',
            ],
            'paid' => [
                'await'     => 'Avventer betaling',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Du mottar denne e-posten med faktura til :customer, pålydende :amount.',
        'button'            => 'Betal nå',
    ],

];
