<?php

return [

    'invoice_number'    => 'Číslo faktury',
    'invoice_date'      => 'Datum faktury',
    'total_price'       => 'Celková cena',
    'due_date'          => 'Datum splatnosti',
    'order_number'      => 'Číslo objednávky',
    'bill_to'           => 'Faktura pro',

    'quantity'          => 'Množství',
    'price'             => 'Cena',
    'sub_total'         => 'Mezisoučet',
    'discount'          => 'Sleva',
    'tax_total'         => 'Daň celkem',
    'total'             => 'Celkem',

    'item_name'         => 'Jméno položky | Jméno položek',

    'show_discount'     => ':discount% Sleva',
    'add_discount'      => 'Přidat slevu',
    'discount_desc'     => 'z propočtu',

    'payment_due'       => 'Splatnost platby',
    'paid'              => 'Zaplaceno',
    'histories'         => 'Historie',
    'payments'          => 'Platby',
    'add_payment'       => 'Přidat platbu',
    'mark_paid'         => 'Označit jako zaplaceno',
    'mark_sent'         => 'Označit odesláno',
    'download_pdf'      => 'Stáhnout PDF',
    'send_mail'         => 'Poslat email',
    'all_invoices'      => 'Přihlašte se pro zobrazení všech faktur',

    'status' => [
        'draft'         => 'Koncept',
        'sent'          => 'Odesláno',
        'viewed'        => 'Zobrazeno',
        'approved'      => 'Schváleno',
        'partial'       => 'Častečná',
        'paid'          => 'Zaplaceno',
    ],

    'messages' => [
        'email_sent'     => 'Fakturační email byl úspěšně odeslán!',
        'marked_sent'    => 'Faktura byla úspěšně označena jako odeslaná!',
        'email_required' => 'Zákazník nemá uvedenou emailovou adresu!',
        'draft'          => 'Toto je <b>KONCEPT</b> faktury a bude promítnut do grafů jakmile bude odeslán.',

        'status' => [
            'created'   => 'Vytvořeno :date',
            'send'      => [
                'draft'     => 'Neodesláno',
                'sent'      => 'Odesláno dne :date',
            ],
            'paid'      => [
                'await'     => 'Čekání na platbu',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Obdželi jste tento email protože máte fakturovat :amount zákazníkovi :customer.',
        'button'        => 'Zaplatit',
    ],

];
