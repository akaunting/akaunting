<?php

return [

    'bill_number'           => 'Inkoopfactuurnummer',
    'bill_date'             => 'Inkoopfactuurdatum',
    'bill_amount'           => 'Inkoopfactuurbedrag',
    'total_price'           => 'Totaalprijs',
    'due_date'              => 'Vervaldatum',
    'order_number'          => 'Bestelnummer',
    'bill_from'             => 'Inkoopfactuur van',

    'quantity'              => 'Aantal',
    'price'                 => 'Prijs',
    'sub_total'             => 'Subtotaal',
    'discount'              => 'Korting',
    'item_discount'         => 'Regelkorting',
    'tax_total'             => 'Totaal BTW',
    'total'                 => 'Totaal',

    'item_name'             => 'Artikelnaam|Artikelnamen',
    'recurring_bills'       => 'Terugkerende Inkoopfactuur|Terugkerende Inkoopfacturen',

    'show_discount'         => ':discount% korting',
    'add_discount'          => 'Korting toevoegen',
    'discount_desc'         => 'van subtotaal',

    'payment_made'          => 'Betaling verricht',
    'payment_due'           => 'Te betalen voor',
    'amount_due'            => 'Verschuldigd bedrag',
    'paid'                  => 'Betaald',
    'histories'             => 'Geschiedenis',
    'payments'              => 'Betalingen',
    'add_payment'           => 'Betaling toevoegen',
    'mark_paid'             => 'Als betaald markeren',
    'mark_received'         => 'Als ontvangen markeren',
    'mark_cancelled'        => 'Als geannuleerd markeren',
    'download_pdf'          => 'PDF downloaden',
    'send_mail'             => 'E-mail versturen',
    'create_bill'           => 'Inkoopfactuur aanmaken',
    'receive_bill'          => 'Inkoopfactuur ontvangen',
    'make_payment'          => 'Betaling uitvoeren',

    'form_description' => [
        'billing'           => 'De factureringsgegevens verschijnen op uw inkoopfactuur. De inkoopfactuurdatum wordt gebruikt in het dashboard en in rapporten. Selecteer de datum waarop u verwacht te betalen als vervaldatum.',
    ],

    'messages' => [
        'draft'             => 'Dit is een <b>CONCEPT</b> inkoopfactuur en zal worden meegenomen in de statistieken zodra deze is ontvangen.',

        'status' => [
            'created'       => 'Aangemaakt op :date',
            'receive' => [
                'draft'     => 'Niet ontvangen',
                'received'  => 'Ontvangen op :date',
            ],
            'paid' => [
                'await'     => 'In afwachting van betaling',
            ],
        ],
    ],

];
