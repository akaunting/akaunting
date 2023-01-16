<?php

return [

    'invoice_number'        => 'Numărul facturii',
    'invoice_date'          => 'Data facturii',
    'invoice_amount'        => 'Suma facturii',
    'total_price'           => 'Preț total',
    'due_date'              => 'Scadenta',
    'order_number'          => 'Număr de comandă',
    'bill_to'               => 'Facturaţi Către',
    'cancel_date'           => 'Data anulării',

    'quantity'              => 'Cantitate',
    'price'                 => 'Preț',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Reducere',
    'item_discount'         => 'Linie reducere',
    'tax_total'             => 'Total taxe',
    'total'                 => 'Total',

    'item_name'             => 'Articol|Articole
Nume articol|Nume articole',
    'recurring_invoices'    => 'Factură recurentă|Facturi recurente',

    'show_discount'         => ':discount% Reducere',
    'add_discount'          => 'Adauga Reducere',
    'discount_desc'         => 'din subtotal',

    'payment_due'           => 'Plata scadenta',
    'paid'                  => 'Plătit',
    'histories'             => 'Istoric',
    'payments'              => 'Plăți',
    'add_payment'           => 'Adauga plata',
    'mark_paid'             => 'Marcheaza ca si Platit',
    'mark_sent'             => 'Marcheaza ca si Trimis',
    'mark_viewed'           => 'Marchează vizualizat',
    'mark_cancelled'        => 'Marchează anulat',
    'download_pdf'          => 'Descarca PDF',
    'send_mail'             => 'Trimite Email',
    'all_invoices'          => 'Autentifică-te pentru a vedea toate facturile',
    'create_invoice'        => 'Crează factură',
    'send_invoice'          => 'Trimite factură',
    'get_paid'              => 'Primește plată',
    'accept_payments'       => 'Acceptă plăți online',
    'payment_received'      => 'Plată primită',

    'form_description' => [
        'billing'           => 'Detaliile de facturare apar în factura ta. Data facturii este utilizată în tabloul de bord și în rapoarte. Selectați data la care te aștepți să primiți plata ca Dată scadentă.',
    ],

    'messages' => [
        'email_required'    => 'Nu exista adresa de email pentru acest client!',
        'draft'             => 'Acesta este o <b>CIORNĂ</b> și va putea fi vizualizată la grafice după ce este primită.',

        'status' => [
            'created'       => 'Creat în :date',
            'viewed'        => 'Vizualizat',
            'send' => [
                'draft'     => 'Netrimis',
                'sent'      => 'Trimis în :date',
            ],
            'paid' => [
                'await'     => 'În aşteptarea plăţii',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user a creat această factură la :date',
        'create_recurring'  => ':user a creat acest șablon recurent la :date',
        'schedule'          => 'Repetați fiecare :interval :frequency din :date',
        'children'          => ':count facturile care au fost create automat',
    ],

    'share' => [
        'show_link'         => 'Clientul tău poate vizualiza factura la acest link',
        'copy_link'         => 'Copiază link-ul și distribuie-l cu clientul tău.',
        'success_message'   => 'Link de distribuire a fost copiat în clipboard!',
    ],

    'sticky' => [
        'description'       => 'Previzualizați modul în care clientul va vedea versiunea web a facturii.',
    ],

];
