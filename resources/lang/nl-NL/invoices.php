<?php

return [

    'invoice_number'    => 'Factuurnummer',
    'invoice_date'      => 'Factuur datum',
    'total_price'       => 'Totaalprijs',
    'due_date'          => 'Vervaldatum',
    'order_number'      => 'Bestelnummer',
    'bill_to'           => 'Factuur voor',

    'quantity'          => 'Aantal',
    'price'             => 'Prijs',
    'sub_total'         => 'Subtotaal',
    'discount'          => 'Korting',
    'tax_total'         => 'Totaal BTW',
    'total'             => 'Totaal',

    'item_name'         => 'Artikelnaam|Artikelnamen',

    'show_discount'     => ':discount% Korting',
    'add_discount'      => 'Korting toevoegen',
    'discount_desc'     => 'van subtotaal',

    'payment_due'       => 'Te betalen voor',
    'paid'              => 'Betaald',
    'histories'         => 'Geschiedenis',
    'payments'          => 'Betalingen',
    'add_payment'       => 'Een betaling toevoegen',
    'mark_paid'         => 'Als betaald markeren',
    'mark_sent'         => 'Als verstuurd markeren',
    'download_pdf'      => 'PDF downloaden',
    'send_mail'         => 'E-mail versturen',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Concept',
        'sent'          => 'Verzonden',
        'viewed'        => 'Bekeken',
        'approved'      => 'Goedgekeurd',
        'partial'       => 'Gedeeltelijk',
        'paid'          => 'Betaald',
    ],

    'messages' => [
        'email_sent'     => 'Factuur is succesvol per e-mail verzonden!',
        'marked_sent'    => 'Factuur is succesvol als verzonden gemarkeerd!',
        'email_required' => 'Er is geen e-mailadres bekend van deze klant!',
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
        'message'       => 'U ontvangt deze e-mail omdat u :amount aanstaande factuur heeft voor klant :customer.',
        'button'        => 'Nu betalen',
    ],

];
