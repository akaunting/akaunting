<?php

return [

    'bill_number'           => 'Rēķina numurs',
    'bill_date'             => 'Rēķina datums',
    'bill_amount'           => 'Rēķina summa',
    'total_price'           => 'Kopējā summa',
    'due_date'              => 'Apmaksas termiņš',
    'order_number'          => 'Pasūtījuma numurs',
    'bill_from'             => 'Piegādātājs (pakalpojuma sniedzējs)',

    'quantity'              => 'Daudzums',
    'price'                 => 'Cena',
    'sub_total'             => 'Starpsumma',
    'discount'              => 'Atlaide',
    'item_discount'         => 'Līnijas atlaide',
    'tax_total'             => 'Nodokļu kopsumma',
    'total'                 => 'Kopā',

    'item_name'             => 'Nosaukums|Nosaukumi',
    'recurring_bills'       => 'Atkārtots rēķins|Atkārtoti rēķini',

    'show_discount'         => 'Atlaide :discount%',
    'add_discount'          => 'Pievienot atlaidi',
    'discount_desc'         => 'no starpsummas',

    'payment_made'          => 'Maksājums veikts',
    'payment_due'           => 'Kavēts rēķins',
    'amount_due'            => 'Kavēta summa',
    'paid'                  => 'Samaksāts',
    'histories'             => 'Vēsture',
    'payments'              => 'Maksājumi',
    'add_payment'           => 'Pievienot maksājumu',
    'mark_paid'             => 'Atzīmēt kā samaksātu',
    'mark_received'         => 'Atzīmēt kā saņemtu',
    'mark_cancelled'        => 'Atzīmēt kā atceltu',
    'download_pdf'          => 'Lejupielādēt PDF',
    'send_mail'             => 'Sūtīt e-pastu',
    'create_bill'           => 'Izrakstīt rēķinu',
    'receive_bill'          => 'Saņemt rēķinu',
    'make_payment'          => 'Veikt maksājumu',

    'form_description' => [
        'billing'           => 'Norēķinu informācija tiek parādīta jūsu rēķinā. Informācijas panelī un pārskatos tiek izmantots rēķina datums. Kā Apmaksas datumu atlasiet datumu, kurā plānojat veikt maksājumu.',
    ],

    'messages' => [
        'draft'             => 'Šī ir rēķina <b>SAGATAVE</b> un tiks atspoguļota diagrammās pēc saņemšanas.',

        'status' => [
            'created'       => 'Izveidots :date',
            'receive' => [
                'draft'     => 'Nav saņemts',
                'received'  => 'Saņemts: :date',
            ],
            'paid' => [
                'await'     => 'Gaida maksājumu',
            ],
        ],
    ],

];
