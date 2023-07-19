<?php

return [

    'invoice_number'        => 'Rēķina numurs',
    'invoice_date'          => 'Rēķina datums',
    'invoice_amount'        => 'Pavadzīmes summa',
    'total_price'           => 'Kopējā summa',
    'due_date'              => 'Apmaksas termiņš',
    'order_number'          => 'Pasūtījuma numurs',
    'bill_to'               => 'Saņēmējs',
    'cancel_date'           => 'Atcelt datumu',

    'quantity'              => 'Daudzums',
    'price'                 => 'Cena',
    'sub_total'             => 'Starpsumma',
    'discount'              => 'Atlaide',
    'item_discount'         => 'Rindas atlaide',
    'tax_total'             => 'Nodokļu kopsumma',
    'total'                 => 'Summa kopā',

    'item_name'             => 'Nosaukums|Nosaukums',
    'recurring_invoices'    => 'Atkārtots rēķins|Atkārtoti rēķini',

    'show_discount'         => ':discount% atlaide',
    'add_discount'          => 'Pievieno atlaidi',
    'discount_desc'         => 'no summas',

    'payment_due'           => 'Apmaksas termiņš',
    'paid'                  => 'Samaksāts',
    'histories'             => 'Vēsture',
    'payments'              => 'Maksājumi',
    'add_payment'           => 'Pievienot maksājumu',
    'mark_paid'             => 'Atzīmēt kā samaksāts',
    'mark_sent'             => 'Atzīmēt kā nosūtītu',
    'mark_viewed'           => 'Atzīmēt skatīto',
    'mark_cancelled'        => 'Atzīme atcelta',
    'download_pdf'          => 'Lejupielādēt PDF',
    'send_mail'             => 'Sūtīt e-pastu',
    'all_invoices'          => 'Pierakstīties, lai skatītu visus rēķinus',
    'create_invoice'        => 'Izveidot rēķinu',
    'send_invoice'          => 'Sūtīt rēķinu',
    'get_paid'              => 'Saņemt apmaksu',
    'accept_payments'       => 'Pieņemt tiešsaistes maksājumus',
    'payments_received'     => 'Saņemtie maksājumi',

    'form_description' => [
        'billing'           => 'Norēķinu informācija tiek parādīta jūsu rēķinā. Informācijas panelī un pārskatos tiek izmantots rēķina datums. Kā Apmaksas datumu atlasiet datumu, kurā plānojat veikt maksājumu.',
    ],

    'messages' => [
        'email_required'    => 'Pircējam nav norādīta e-pasta adrese!',
        'draft'             => 'Šis ir <b>melnraksts</b> rēķinam un tas atspoguļosies diagrammās, pēc tam, kad tas tiks iespējots / nosūtīts.',

        'status' => [
            'created'       => 'Izveidots :datums',
            'viewed'        => 'Skatīts',
            'send' => [
                'draft'     => 'Nav nosūtīts',
                'sent'      => 'Nosūtīts :datums',
            ],
            'paid' => [
                'await'     => 'Gaida maksājumu',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user izveidoja šo pārskaitījumu :date',
        'create_recurring'  => ':user izveidoja šo periodisko veidni :date',
        'schedule'          => 'Atkārtojiet katru :interval :frequency kopš :date',
        'children'          => ':count rēķini tika izveidoti automātiski',
    ],

    'share' => [
        'show_link'         => 'Jūsu klients var apskatīt rēķinu šajā saitē',
        'copy_link'         => 'Kopējiet saiti un kopīgojiet to ar savu klientu.',
        'success_message'   => 'Kopīgošanas saite ir iekopēta starpliktuvē!',
    ],

    'sticky' => [
        'description'       => 'Jūsu priekšskatījums, kā jūsu klients redzēs jūsu rēķina tīmekļa versiju.',
    ],

];
