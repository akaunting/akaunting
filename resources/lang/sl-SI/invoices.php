<?php

return [

    'invoice_number'        => 'Številka računa',
    'invoice_date'          => 'Datum računa',
    'invoice_amount'        => 'Znesek računa',
    'total_price'           => 'Skupna cena',
    'due_date'              => 'Datum zapadlosti',
    'order_number'          => 'Številka naročila',
    'bill_to'               => 'Račun za',

    'quantity'              => 'Količina',
    'price'                 => 'Cena',
    'sub_total'             => 'Delna vsota',
    'discount'              => 'Popust',
    'item_discount'         => 'Popust',
    'tax_total'             => 'Davek skupaj',
    'total'                 => 'Skupaj',

    'item_name'             => 'Ime artikla|Imena artiklov',

    'show_discount'         => ':discount% popust',
    'add_discount'          => 'Dodaj popust',
    'discount_desc'         => 'od skupno',

    'payment_due'           => 'Rok plačila',
    'paid'                  => 'Plačano',
    'histories'             => 'Zgodovine',
    'payments'              => 'Plačila',
    'add_payment'           => 'Dodaj plačilo',
    'mark_paid'             => 'Označi kot plačano',
    'mark_sent'             => 'Označi kot poslano',
    'mark_viewed'           => 'Označi kot ogledano',
    'mark_cancelled'        => 'Označi preklicano',
    'download_pdf'          => 'Prenesi PDF',
    'send_mail'             => 'Pošljite e-pošto',
    'all_invoices'          => 'Za pregled vseh računov se vpiši',
    'create_invoice'        => 'Ustvari račun',
    'send_invoice'          => 'Pošlji račun',
    'get_paid'              => 'Prejmi plačilo',
    'accept_payments'       => 'Sprejmi Spletna Plačila',

    'messages' => [
        'email_required'    => 'Za to stranko ne obstaja elektronski naslov!',
        'draft'             => 'To je <b>osnutek</b> računa, ki bo v grafikonih viden šele, ko bo poslan.',

        'status' => [
            'created'       => 'Ustvarjeno dne :date',
            'viewed'        => 'Ogledano',
            'send' => [
                'draft'     => 'Ni poslano',
                'sent'      => 'Poslano :date',
            ],
            'paid' => [
                'await'     => 'Čakanje na plačilo',
            ],
        ],
    ],

];
