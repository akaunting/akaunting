<?php

return [

    'bill_number'       => 'Číslo účtu',
    'bill_date'         => 'Dátum vystavenia',
    'total_price'       => 'Celková cena',
    'due_date'          => 'Dátum splatnosti',
    'order_number'      => 'Číslo objednávky',
    'bill_from'         => 'Platba z',

    'quantity'          => 'Množstvo',
    'price'             => 'Cena',
    'sub_total'         => 'Medzisúčet',
    'discount'          => 'Zľava',
    'tax_total'         => 'Daň celkom',
    'total'             => 'Spolu',

    'item_name'         => 'Názov položky|Názvy položiek',

    'show_discount'     => ':discount% zľava',
    'add_discount'      => 'Pridať zľavu',
    'discount_desc'     => 'z celku',

    'payment_due'       => 'Dátum splatnosti',
    'amount_due'        => 'Splatná suma',
    'paid'              => 'Zaplatené',
    'histories'         => 'História',
    'payments'          => 'Platby',
    'add_payment'       => 'Pridať platbu',
    'mark_received'     => 'Zmeniť na prijaté',
    'download_pdf'      => 'Stiahnuť PDF',
    'send_mail'         => 'Odoslať e-mail',

    'statuses' => [
        'draft'         => 'Koncept',
        'received'      => 'Prijaté',
        'partial'       => 'Čiastočné',
        'paid'          => 'Zaplatené',
    ],

    'messages' => [
        'received'      => 'Bolo úspěšně označené ako prijaté!',
        'draft'          => 'To je <b>Návrh</b> faktúry, po odoslaní bude zobrazená v grafe.',

        'status' => [
            'created'   => 'Vytvorené :date',
            'receive'      => [
                'draft'     => 'Nebola odoslaná',
                'received'  => 'Doručené :date',
            ],
            'paid'      => [
                'await'     => 'Čaká sa na platbu',
            ],
        ],
    ],

];
