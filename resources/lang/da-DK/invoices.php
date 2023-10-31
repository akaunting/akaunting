<?php

return [

    'invoice_number'        => 'Faktura nummer',
    'invoice_date'          => 'Faktura dato',
    'invoice_amount'        => 'Fakturabeløb',
    'total_price'           => 'Total pris',
    'due_date'              => 'Forfaldsdato',
    'order_number'          => 'Ordrenummer',
    'bill_to'               => 'Faktura til',
    'cancel_date'           => 'Annuller Dato',

    'quantity'              => 'Antal',
    'price'                 => 'Pris',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Rabat',
    'item_discount'         => 'Linjerabat',
    'tax_total'             => 'Moms i alt',
    'total'                 => 'I alt',

    'item_name'             => 'Vare navn|Vare navne',
    'recurring_invoices'    => 'Tilbagevendende Faktura|Tilbagevendende Fakturaer',

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
    'mark_viewed'           => 'Marker Set',
    'mark_cancelled'        => 'Marker Annulleret',
    'download_pdf'          => 'Download som PDF',
    'send_mail'             => 'Send E-mail',
    'all_invoices'          => 'Log ind for at se alle fakturaer',
    'create_invoice'        => 'Opret faktura',
    'send_invoice'          => 'Send faktura',
    'get_paid'              => 'Betal',
    'accept_payments'       => 'Accepter onlinebetalinger',
    'payments_received'     => 'Betalinger modtaget',

    'form_description' => [
        'billing'           => 'Faktureringsoplysninger vises på din faktura. Faktureringsdato bruges i skrivebordet og rapporter. Vælg den dato, du forventer at blive betalt som forfaldsdato.',
    ],

    'messages' => [
        'email_required'    => 'Ingen E-mail-adresse for kunden!',
        'totals_required'   => 'Faktura totaler kræves Rediger venligst :type og gem den igen.',

        'draft'             => 'Dette er et <b>UDKAST</b> til faktura og vil først blive vist i oversigten, når den er sendt.',

        'status' => [
            'created'       => 'Oprettet den :date',
            'viewed'        => 'Set',
            'send' => [
                'draft'     => 'Ikke sendt',
                'sent'      => 'Sendt den :date',
            ],
            'paid' => [
                'await'     => 'Afventer betaling',
            ],
        ],

        'name_or_description_required' => 'Din faktura skal vise mindst en af <b>:name</b> eller <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Din kunde kan se fakturaen på dette link',
        'copy_link'         => 'Kopier linket og del det med din kunde.',
        'success_message'   => 'Delingslink kopieret til udklipsholder!',
    ],

    'sticky' => [
        'description'       => 'Du forhåndsviser, hvordan din kunde vil se webversionen af din faktura.',
    ],

];
