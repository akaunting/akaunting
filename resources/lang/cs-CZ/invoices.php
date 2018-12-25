<?php

return [

    'invoice_number'        => 'Číslo faktury',
    'invoice_date'          => 'Datum faktury',
    'total_price'           => 'Celková cena',
    'due_date'              => 'Datum splatnosti',
    'order_number'          => 'Číslo objednávky',
    'bill_to'               => 'Faktura pro',

    'quantity'              => 'Množství',
    'price'                 => 'Cena',
    'sub_total'             => 'Mezisoučet',
    'discount'              => 'Sleva',
    'tax_total'             => 'Daň celkem',
    'total'                 => 'Celkem',

    'item_name'             => 'Název položky|Název položek',

    'show_discount'         => 'Sleva :discount%',
    'add_discount'          => 'Přidat slevu',
    'discount_desc'         => 'z mezisoučtu',

    'payment_due'           => 'Splatnost platby',
    'paid'                  => 'Zaplaceno',
    'histories'             => 'Historie',
    'payments'              => 'Platby',
    'add_payment'           => 'Přidat platbu',
    'mark_paid'             => 'Označit jako zaplaceno',
    'mark_sent'             => 'Označit jako odesláno',
    'download_pdf'          => 'Stáhnout PDF',
    'send_mail'             => 'Odeslat e-mail',
    'all_invoices'          => 'Přihlašte se pro zobrazení všech faktur',
    'create_invoice'        => 'Vytvoření faktury',
    'send_invoice'          => 'Odeslání faktury',
    'get_paid'              => 'Uhrazení faktury',

    'status' => [
        'draft'             => 'Koncept',
        'sent'              => 'Odesláno',
        'viewed'            => 'Zobrazeno',
        'approved'          => 'Schváleno',
        'partial'           => 'Častečná',
        'paid'              => 'Zaplaceno',
    ],

    'messages' => [
        'email_sent'        => 'E-mail s fakturou byl úspěšně odeslán!',
        'marked_sent'       => 'Faktura byla úspěšně označena jako odeslaná!',
        'email_required'    => 'Zákazník nemá uvedenou e-mailovou adresu!',
        'draft'             => 'Toto je <b>KONCEPT</b> faktury. Faktura bude promítnuta do grafů, jakmile bude odeslána.',

        'status' => [
            'created'       => 'Vytvořeno :date',
            'send' => [
                'draft'     => 'Neodesláno',
                'sent'      => 'Odesláno dne :date',
            ],
            'paid' => [
                'await'     => 'Čeká na platbu',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Obdrželi jste tento email, protože máte fakturovat :amount zákazníkovi :customer.',
        'button'            => 'Zaplatit',
    ],

];
