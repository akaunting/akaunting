<?php

return [

    'bill_number'           => 'Číslo faktury',
    'bill_date'             => 'Datum faktury',
    'total_price'           => 'Celková cena',
    'due_date'              => 'Datum splatnosti',
    'order_number'          => 'Číslo objednávky',
    'bill_from'             => 'Faktura od',

    'quantity'              => 'Množství',
    'price'                 => 'Cena',
    'sub_total'             => 'Mezisoučet',
    'discount'              => 'Sleva',
    'item_discount'         => 'Řádková sleva',
    'tax_total'             => 'DPH celkem',
    'total'                 => 'Celkem',

    'item_name'             => 'Název položky|Název položek',

    'show_discount'         => 'Sleva :discount%',
    'add_discount'          => 'Přidat slevu',
    'discount_desc'         => 'z mezisoučtu',

    'payment_due'           => 'Splatnost faktury',
    'amount_due'            => 'Dlužná částka',
    'paid'                  => 'Zaplaceno',
    'histories'             => 'Historie',
    'payments'              => 'Platby',
    'add_payment'           => 'Přidat platbu',
    'mark_paid'             => 'Označit jako zaplacené',
    'mark_received'         => 'Označit jako přijatou',
    'mark_cancelled'        => 'Označit jako zrušené',
    'download_pdf'          => 'Stáhnout PDF',
    'send_mail'             => 'Odeslat e-mail',
    'create_bill'           => 'Vytvoření faktury',
    'receive_bill'          => 'Příjem faktury',
    'make_payment'          => 'Platba faktury',

    'messages' => [
        'draft'             => 'Toto je <b>KONCEPT</b> faktury. Faktura bude promítnuta do grafů, jakmile bude zaplacena.',

        'status' => [
            'created'       => 'Vytvořeno :date',
            'receive' => [
                'draft'     => 'Neodesláno',
                'received'  => 'Přijato :date',
            ],
            'paid' => [
                'await'     => 'Čeká na platbu',
            ],
        ],
    ],

];
