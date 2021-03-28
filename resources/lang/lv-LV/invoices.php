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
    'mark_viewed'           => 'Mark Viewed',
    'download_pdf'          => 'Lejupielādēt PDF',
    'send_mail'             => 'Sūtīt e-pastu',
    'all_invoices'          => 'Pierakstīties, lai skatītu visus rēķinus',
    'create_invoice'        => 'Izveidot rēķinu',
    'send_invoice'          => 'Sūtīt rēķinu',
    'get_paid'              => 'Saņemt apmaksu',
    'accept_payments'       => 'Pieņemt tiešsaistes maksājumus',

    'statuses' => [
        'draft'             => 'Draft',
        'sent'              => 'Sent',
        'viewed'            => 'Viewed',
        'approved'          => 'Approved',
        'partial'           => 'Partial',
        'paid'              => 'Paid',
        'overdue'           => 'Overdue',
        'unpaid'            => 'Unpaid',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent!',
        'marked_sent'       => 'Invoice marked as sent!',
        'marked_paid'       => 'Invoice marked as paid!',
        'email_required'    => 'Pircējam nav norādīta e-pasta adrese!',
        'draft'             => 'Šis ir <b>melnraksts</b> rēķinam un tas atspoguļosies diagrammās, pēc tam, kad tas tiks iespējots / nosūtīts.',

        'status' => [
            'created'       => 'Izveidots: datums',
            'viewed'        => 'Viewed',
            'send' => [
                'draft'     => 'Nav nosūtīts',
                'sent'      => 'Nosūtīts: datums',
            ],
            'paid' => [
                'await'     => 'Gaidāmie maksājumi',
            ],
        ],
    ],

];
