<?php

return [

    'invoice_number'    => 'Číslo faktúry',
    'invoice_date'      => 'Dátum fakturácie',
    'total_price'       => 'Celková cena',
    'due_date'          => 'Dátum splatnosti',
    'order_number'      => 'Číslo objednávky',
    'bill_to'           => 'Odberateľ',

    'quantity'          => 'Množstvo',
    'price'             => 'Cena',
    'sub_total'         => 'Medzisúčet',
    'discount'          => 'Zľava',
    'tax_total'         => 'Daň celkom',
    'total'             => 'Spolu',

    'item_name'         => 'Názov položky|Názvy položiek',

    'show_discount'     => ': zľava % zľava',
    'add_discount'      => 'Pridať zľavu',
    'discount_desc'     => 'z celku',

    'payment_due'       => 'Splatnosť',
    'paid'              => 'Zaplatené',
    'histories'         => 'História',
    'payments'          => 'Platby',
    'add_payment'       => 'Pridať platbu',
    'mark_paid'         => 'Zmeniť na zaplatené',
    'mark_sent'         => 'Zmeniť na odoslané',
    'download_pdf'      => 'Stiahnuť PDF',
    'send_mail'         => 'Odoslať e-mail',
    'all_invoices'      => 'Prihláste sa pre zobrazenie všetkých faktúr',

    'statuses' => [
        'draft'         => 'Koncept',
        'sent'          => 'Odoslané',
        'viewed'        => 'Zobrazené',
        'approved'      => 'Schválené',
        'partial'       => 'Čiastočný',
        'paid'          => 'Zaplatené',
    ],

    'messages' => [
        'email_sent'     => 'Email s faktúrou bol úspešne odoslaný!',
        'marked_sent'    => 'Faktúra bola označená ako odoslaná!',
        'email_required' => 'Žiadna e-mailová adresa pre tohto zákazníka!',
        'draft'          => 'To je <b>Návrh</b> faktúry, po odoslaní bude zobrazená v grafe.',

        'status' => [
            'created'   => 'Vytvorené :date',
            'send'      => [
                'draft'     => 'Nebola odoslaná',
                'sent'      => 'Odoslané :date',
            ],
            'paid'      => [
                'await'     => 'Čaká sa na platbu',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Dostávate tento e-mail, pretože máte prichádzajúce :amount faktúry pre :customer zákazníka.',
        'button'        => 'Zaplatiť teraz',
    ],

];
