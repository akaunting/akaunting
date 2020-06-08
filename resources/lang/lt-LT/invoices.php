<?php

return [

    'invoice_number'        => 'Sąskaitos-faktūros numeris',
    'invoice_date'          => 'Sąskaitos-faktūros data',
    'total_price'           => 'Bendra kaina',
    'due_date'              => 'Terminas',
    'order_number'          => 'Užsakymo numeris',
    'bill_to'               => 'Pirkėjas',

    'quantity'              => 'Kiekis',
    'price'                 => 'Kaina',
    'sub_total'             => 'Tarpinė suma',
    'discount'              => 'Nuolaida',
    'item_discount'         => 'Nuolaida',
    'tax_total'             => 'Mokesčių suma',
    'total'                 => 'Iš viso',

    'item_name'             => 'Prekė/paslauga|Prekės/paslaugos',

    'show_discount'         => ':discount% nuolaida',
    'add_discount'          => 'Pridėti nuolaidą',
    'discount_desc'         => 'tarpinė suma',

    'payment_due'           => 'Mokėjimo terminas',
    'paid'                  => 'Apmokėta',
    'histories'             => 'Istorijos',
    'payments'              => 'Mokėjimai',
    'add_payment'           => 'Pridėti mokėjimą',
    'mark_paid'             => 'Pažymėti kaip apmokėtą',
    'mark_sent'             => 'Pažymėti kaip išsiųstą',
    'mark_viewed'           => 'Pažymėti kaip peržiūrėtą',
    'mark_cancelled'        => 'Pažymėti kaip atšauktą',
    'download_pdf'          => 'Parsisiųsti PDF',
    'send_mail'             => 'Siųsti laišką',
    'all_invoices'          => 'Prisijunkite norėdami peržiūrėti visas sąskaitas faktūras',
    'create_invoice'        => 'Sukurti sąskaitą-faktūrą',
    'send_invoice'          => 'Siųsti sąskaitą-faktūrą',
    'get_paid'              => 'Gauti apmokėjimą',
    'accept_payments'       => 'Priimti atsiskaitymus internetu',

    'statuses' => [
        'draft'             => 'Juodraštis',
        'sent'              => 'Išsiųsta',
        'viewed'            => 'Peržiūrėta',
        'approved'          => 'Patvirtinta',
        'partial'           => 'Dalinis',
        'paid'              => 'Apmokėta',
        'overdue'           => 'Vėluojanti',
        'unpaid'            => 'Neapmokėta',
        'cancelled'         => 'Atšaukta',
    ],

    'messages' => [
        'email_sent'        => 'Sąskaita-faktūra išsiųsta el. paštu!',
        'marked_sent'       => 'Sąskaita-faktūra pažymėta kaip išsiųsta!',
        'marked_paid'       => 'Sąskaita-faktūra pažymėta kaip apmokėta!',
        'marked_viewed'     => 'Sąskaita-faktūra pažymėta kaip peržiūrėta!',
        'marked_cancelled'  => 'Sąskaita-faktūra pažymėta kaip atšaukta!',
        'email_required'    => 'Klientas neturi el. pašto!',
        'draft'             => 'Tai yra <b>JUODRAŠTINĖ</b> sąskaita ir ji bus įtraukta į grafikus po to kai bus išsiųsta.',

        'status' => [
            'created'       => 'Sukurta :date',
            'viewed'        => 'Peržiūrėta',
            'send' => [
                'draft'     => 'Neišsiųsta',
                'sent'      => 'Išsiųsta :date',
            ],
            'paid' => [
                'await'     => 'Laukiama apmokėjimo',
            ],
        ],
    ],

];
