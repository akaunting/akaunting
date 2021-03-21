<?php

return [

    'invoice_number'        => 'Laskun numero',
    'invoice_date'          => 'Laskun päivämäärä',
    'total_price'           => 'Hinta yhteensä',
    'due_date'              => 'Eräpäivä',
    'order_number'          => 'Tilausnumero',
    'bill_to'               => 'Laskutusosoite',

    'quantity'              => 'Määrä',
    'price'                 => 'Hinta',
    'sub_total'             => 'Välisumma',
    'discount'              => 'Alennus',
    'item_discount'         => 'Rivialennus',
    'tax_total'             => 'Vero Yhteensä',
    'total'                 => 'Yhteensä',

    'item_name'             => 'Tuotteen Nimi|Tuotteiden Nimet',

    'show_discount'         => ':discount% Alennus',
    'add_discount'          => 'Lisää alennus',
    'discount_desc'         => 'välisummasta',

    'payment_due'           => 'Maksu erääntyy',
    'paid'                  => 'Maksettu',
    'histories'             => 'Historia',
    'payments'              => 'Maksut',
    'add_payment'           => 'Lisää maksu',
    'mark_paid'             => 'Merkitse maksetuksi',
    'mark_sent'             => 'Merkitse lähetetyksi',
    'mark_viewed'           => 'Merkitse katsotuksi',
    'mark_cancelled'        => 'Merkitse peruutetuksi',
    'download_pdf'          => 'Lataa PDF',
    'send_mail'             => 'Lähetä Sähköposti',
    'all_invoices'          => 'Kirjaudu sisään nähdäksesi kaikki laskut',
    'create_invoice'        => 'Luo lasku',
    'send_invoice'          => 'Lähetä lasku',
    'get_paid'              => 'Maksettu',
    'accept_payments'       => 'Vastaanota Online-maksuja',

    'messages' => [
        'email_required'    => 'Ei sähköpostiosoitetta tälle asiakkaalle!',
        'draft'             => 'Tämä on <b>luonnoslasku</b> ja se heijastuu kirjanpitoon vasta lähettämisen jälkeen.',

        'status' => [
            'created'       => 'Luotu :date',
            'viewed'        => 'Katsottu',
            'send' => [
                'draft'     => 'Ei lähetetty',
                'sent'      => 'Lähetetty :date',
            ],
            'paid' => [
                'await'     => 'Odottaa maksua',
            ],
        ],
    ],

];
