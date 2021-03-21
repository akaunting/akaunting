<?php

return [

    'invoice_number'        => 'Factuurnummer',
    'invoice_date'          => 'Factuur datum',
    'total_price'           => 'Totaalprijs',
    'due_date'              => 'Vervaldatum',
    'order_number'          => 'Bestelnummer',
    'bill_to'               => 'Factuur voor',

    'quantity'              => 'Aantal',
    'price'                 => 'Prijs',
    'sub_total'             => 'Subtotaal',
    'discount'              => 'Korting',
    'item_discount'         => 'Lijn korting',
    'tax_total'             => 'Totaal BTW',
    'total'                 => 'Totaal',

    'item_name'             => 'Artikelnaam|Artikelnamen',

    'show_discount'         => ':discount% Korting',
    'add_discount'          => 'Korting toevoegen',
    'discount_desc'         => 'van subtotaal',

    'payment_due'           => 'Te betalen voor',
    'paid'                  => 'Betaald',
    'histories'             => 'Geschiedenis',
    'payments'              => 'Betalingen',
    'add_payment'           => 'Een betaling toevoegen',
    'mark_paid'             => 'Als betaald markeren',
    'mark_sent'             => 'Als verstuurd markeren',
    'mark_viewed'           => 'Markeren als bekeken',
    'mark_cancelled'        => 'Markeren als geannuleerd',
    'download_pdf'          => 'PDF downloaden',
    'send_mail'             => 'E-mail versturen',
    'all_invoices'          => 'Log in om alle facturen te bekijken',
    'create_invoice'        => 'Factuur maken',
    'send_invoice'          => 'Factuur sturen',
    'get_paid'              => 'Betaling afstemmen',
    'accept_payments'       => 'Online betalingen accepteren',

    'messages' => [
        'email_required'    => 'Er is geen e-mailadres bekend van deze klant!',
        'draft'             => 'Dit is een <b>CONCEPT</b> factuur en zal terugkomen in de statistieken wanneer het verzonden is.',

        'status' => [
            'created'       => 'Gemaakt op :date',
            'viewed'        => 'Bekeken',
            'send' => [
                'draft'     => 'Niet verstuurd',
                'sent'      => 'Verzonden op :date',
            ],
            'paid' => [
                'await'     => 'In afwachting van betaling',
            ],
        ],
    ],

];
