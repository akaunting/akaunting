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
    'download_pdf'          => 'Parsisiųsti PDF',
    'send_mail'             => 'Siųsti laišką',
    'all_invoices'          => 'Prisijunkite norėdami peržiūrėti visas sąskaitas faktūras',
    'create_invoice'        => 'Sukurti sąskaitą-faktūrą',
    'send_invoice'          => 'Siųsti sąskaitą-faktūrą',
    'get_paid'              => 'Gauti apmokėjimą',

    'status' => [
        'draft'             => 'Juodraštis',
        'sent'              => 'Išsiųsta',
        'viewed'            => 'Peržiūrėta',
        'approved'          => 'Patvirtinta',
        'partial'           => 'Dalinis',
        'paid'              => 'Apmokėta',
    ],

    'messages' => [
        'email_sent'        => 'Sąskaitą-faktūrą išsiųsta sėkmingai!',
        'marked_sent'       => 'SF pažymėta kaip išsiųsta sėkmingai!',
        'email_required'    => 'Klientas neturi el. pašto!',
        'draft'             => 'Tai yra <b>JUODRAŠTINĖ</b> sąskaita ir ji bus įtraukta į grafikus po to kai bus išsiųsta.',

        'status' => [
            'created'       => 'Sukurta :date',
            'send' => [
                'draft'     => 'Neišsiųsta',
                'sent'      => 'Išsiųsta :date',
            ],
            'paid' => [
                'await'     => 'Laukiama apmokėjimo',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Jūs gavote šį laišką, nes :customer jums išrašė sąskaitą už :amount.',
        'button'            => 'Apmokėti dabar',
    ],

];
