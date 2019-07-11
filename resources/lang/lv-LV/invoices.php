<?php

return [

    'invoice_number'        => 'Rēķina numurs',
    'invoice_date'          => 'Rēķina datums',
    'total_price'           => 'Kopējā summa',
    'due_date'              => 'Apmaksas termiņš',
    'order_number'          => 'Pasūtījuma numurs',
    'bill_to'               => 'Saņēmējs',

    'quantity'              => 'Daudzums',
    'price'                 => 'Cena',
    'sub_total'             => 'Summa',
    'discount'              => 'Atlaide',
    'tax_total'             => 'Atlaide kopā',
    'total'                 => 'Summa',

    'item_name'             => 'Nosaukums|Nosaukums',

    'show_discount'         => ':discount% atlaide',
    'add_discount'          => 'Pieviento atlaidi',
    'discount_desc'         => 'no summas',

    'payment_due'           => 'Apmaksas termiņš',
    'paid'                  => 'Samaksāts',
    'histories'             => 'Vēsture',
    'payments'              => 'Maksājumi',
    'add_payment'           => 'Pievienot maksājumu',
    'mark_paid'             => 'Atzīmēt kā samaksāts',
    'mark_sent'             => 'Atzīmēt kā nosūtītu',
    'download_pdf'          => 'Lejupielādēt PDF',
    'send_mail'             => 'Sūtīt e-pastu',
    'all_invoices'          => 'Pierakstīties, lai skatītu visus rēķinus',
    'create_invoice'        => 'Izveidot rēķinu',
    'send_invoice'          => 'Sūtīt rēķinu',
    'get_paid'              => 'Saņemt apmaksu',
    'accept_payments'       => 'Pieņemt tiešsaistes maksājumus',

    'status' => [
        'draft'             => 'Sagatave',
        'sent'              => 'Nosūtīts',
        'viewed'            => 'Skatīts',
        'approved'          => 'Apstiprināts',
        'partial'           => 'Daļēji',
        'paid'              => 'Samaksāts',
    ],

    'messages' => [
        'email_sent'        => 'Rēķins veiksmīgi nosūtīts uz e-pastu!',
        'marked_sent'       => 'Rēķins atzīmēts kā nosūtīts!',
        'email_required'    => 'Pircējam nav norādīta e-pasta adrese!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Izveidots: datums',
            'send' => [
                'draft'     => 'Nav nosūtīts',
                'sent'      => 'Nosūtīts: datums',
            ],
            'paid' => [
                'await'     => 'Gaidāmie maksājumi',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Jūs saņemāt šo e-pastu, jo jums ir sagatavots rēķins par summu :amount, Rēķins izrakstīts :customer.',
        'button'            => 'Apmaksāt tagad',
    ],

];
