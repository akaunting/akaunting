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
    'item_discount'         => 'Linjerabatt',
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
    'mark_viewed'           => 'Merk som sett',
    'mark_cancelled'        => 'Merk som kansellert',
    'download_pdf'          => 'Last ned PDF',
    'send_mail'             => 'Send e-post',
    'all_invoices'          => 'Logg inn for å se alle fakturaer',
    'create_invoice'        => 'Opprett faktura',
    'send_invoice'          => 'Send faktura',
    'get_paid'              => 'Få betalt',
    'accept_payments'       => 'Aksepter online betalinger',

    'statuses' => [
        'draft'             => 'Kladd',
        'sent'              => 'Sendt',
        'viewed'            => 'Sett',
        'approved'          => 'Godkjent',
        'partial'           => 'Delvis',
        'paid'              => 'Betalt',
        'overdue'           => 'Forfalt',
        'unpaid'            => 'Ubetalt',
        'cancelled'         => 'Kansellert',
    ],

    'messages' => [
        'email_sent'        => 'Faktura e-post er blitt sendt!',
        'marked_sent'       => 'Faktura merket som sendt!',
        'marked_paid'       => 'Faktura merket som betalt!',
        'marked_viewed'     => 'Faktura merket som sett!',
        'marked_cancelled'  => 'Faktura merket som kansellert!',
        'email_required'    => 'E-postadresse må fylles inn.',
        'draft'             => 'Dette er en <b>KLADD</b> for fakturaen som vil bli oppdatert etter at den er sendt.',

        'status' => [
            'created'       => 'Opprettet :date',
            'viewed'        => 'Sett',
            'send' => [
                'draft'     => 'Ikke sendt',
                'sent'      => 'Sendt :date',
            ],
            'paid' => [
                'await'     => 'Avventer betaling',
            ],
        ],
    ],

];
