<?php

return [

    'bill_number'       => 'Účet číslo',
    'bill_date'         => 'Datum účtu',
    'total_price'       => 'Celková cena',
    'due_date'          => 'Datum splatnosti',
    'order_number'      => 'Číslo objednávky',
    'bill_from'         => 'Platba od',

    'quantity'          => 'Množství',
    'price'             => 'Cena',
    'sub_total'         => 'Mezisoučet',
    'discount'          => 'Sleva',
    'tax_total'         => 'Dph celkem',
    'total'             => 'Celkem',

    'item_name'         => 'Jméno položky | Jméno položek',

    'show_discount'     => ':discount% Sleva',
    'add_discount'      => 'Přidat slevu',
    'discount_desc'     => 'z propočtu',

    'payment_due'       => 'Splatnost platby',
    'amount_due'        => 'Dlužná částka',
    'paid'              => 'Zaplaceno',
    'histories'         => 'Historie',
    'payments'          => 'Platby',
    'add_payment'       => 'Přidat platbu',
    'mark_received'     => 'Označ za přijatou',
    'download_pdf'      => 'Stáhnout PDF',
    'send_mail'         => 'Poslat email',

    'status' => [
        'draft'         => 'Koncept',
        'received'      => 'Přijato',
        'partial'       => 'Častečná',
        'paid'          => 'Zaplaceno',
    ],

    'messages' => [
        'received'      => 'Bylo úspěšně označeno jako přijaté!',
        'draft'          => 'Toto je <b>KONCEPT</b> faktury a bude promítnut do grafů jakmile bude zaplacen.',

        'status' => [
            'created'   => 'Vytvořeno :date',
            'receive'      => [
                'draft'     => 'Neodesláno',
                'received'  => 'Přijato :date',
            ],
            'paid'      => [
                'await'     => 'Čekání na platbu',
            ],
        ],
    ],

];
