<?php

return [

    'bill_number'           => 'Factuurnummer',
    'bill_date'             => 'Factuur datum',
    'total_price'           => 'Totaalprijs',
    'due_date'              => 'Vervaldatum',
    'order_number'          => 'Bestelnummer',
    'bill_from'             => 'Factuur van',

    'quantity'              => 'Aantal',
    'price'                 => 'Prijs',
    'sub_total'             => 'Subtotaal',
    'discount'              => 'Korting',
    'item_discount'         => 'Vaste korting',
    'tax_total'             => 'Totaal BTW',
    'total'                 => 'Totaal',

    'item_name'             => 'Artikelnaam|Artikelnamen',

    'show_discount'         => ':discount% korting',
    'add_discount'          => 'Korting toevoegen',
    'discount_desc'         => 'van subtotaal',

    'payment_due'           => 'Te betalen voor',
    'amount_due'            => 'Verschuldigd bedrag',
    'paid'                  => 'Betaald',
    'histories'             => 'Geschiedenis',
    'payments'              => 'Betalingen',
    'add_payment'           => 'Een betaling toevoegen',
    'mark_paid'             => 'Als betaald markeren',
    'mark_received'         => 'Markeer als ontvangen',
    'mark_cancelled'        => 'Markeren als geannuleerd',
    'download_pdf'          => 'PDF downloaden',
    'send_mail'             => 'Verstuur e-mail',
    'create_bill'           => 'Factuur maken',
    'receive_bill'          => 'Factuur ontvangen',
    'make_payment'          => 'Betaling',

    'statuses' => [
        'draft'             => 'Concept',
        'received'          => 'Ontvangen',
        'partial'           => 'Gedeeltelijk',
        'paid'              => 'Betaald',
        'overdue'           => 'Verlopen',
        'unpaid'            => 'Onbetaald',
        'cancelled'         => 'Geannuleerd',
    ],

    'messages' => [
        'marked_received'   => 'Factuur gemarkeerd als ontvangen!',
        'marked_paid'       => 'Factuur gemarkeerd als betaald!',
        'marked_cancelled'  => 'Factuur gemarkeerd als geannuleerd!',
        'draft'             => 'Dit is een <b>CONCEPT</b> factuur en zal terugkomen in de statistieken wanneer het verzonden is.',

        'status' => [
            'created'       => 'Gemaakt op :date',
            'receive' => [
                'draft'     => 'Niet verstuurd',
                'received'  => 'Ontvangen op :date',
            ],
            'paid' => [
                'await'     => 'In afwachting van betaling',
            ],
        ],
    ],

];
