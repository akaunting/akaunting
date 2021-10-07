<?php

return [

    'invoice_number'        => 'Број на Фактура',
    'invoice_date'          => 'Датум на Фактура',
    'invoice_amount'        => 'Сума на Фактурата',
    'total_price'           => 'Вкупна цена',
    'due_date'              => 'Доспева на',
    'order_number'          => 'Број на нарачка',
    'bill_to'               => 'Фактурирај на',

    'quantity'              => 'Количина',
    'price'                 => 'Цена',
    'sub_total'             => 'Меѓузбир',
    'discount'              => 'Попуст',
    'item_discount'         => 'Попуст на линија',
    'tax_total'             => 'Вкупно данок',
    'total'                 => 'Вкупно',

    'item_name'             => 'Име на ставка|Име на ставките',

    'show_discount'         => ':discount% Попуст',
    'add_discount'          => 'Додади попуст',
    'discount_desc'         => 'од меѓузбир',

    'payment_due'           => 'Доспева за плаќање',
    'paid'                  => 'Платено',
    'histories'             => 'Историја',
    'payments'              => 'Плаќања',
    'add_payment'           => 'Додади плаќање',
    'mark_paid'             => 'Означи платено',
    'mark_sent'             => 'Означи испратено',
    'mark_viewed'           => 'Означи како Видено',
    'mark_cancelled'        => 'Означи како Откажано',
    'download_pdf'          => 'Превземи PDF',
    'send_mail'             => 'Прати е-маил',
    'all_invoices'          => 'Најавете се за да ги видите сите фактури',
    'create_invoice'        => 'Нова Фактура',
    'send_invoice'          => 'Прати ја Фактурата',
    'get_paid'              => 'Плати се',
    'accept_payments'       => 'Прифатете плаќање преку Интернет',

    'messages' => [
        'email_required'    => 'Не постои е-маил адреса за овој клиент!',
        'draft'             => 'Ова е <b>НАЦРТ</b> фактура и ќе се рефлектира на графиконите откако ќе биде испратена.',

        'status' => [
            'created'       => 'Создадена на :date',
            'viewed'        => 'Прегледано',
            'send' => [
                'draft'     => 'Не е испратена',
                'sent'      => 'Испратена на :date',
            ],
            'paid' => [
                'await'     => 'Чекам на плаќање',
            ],
        ],
    ],

];
