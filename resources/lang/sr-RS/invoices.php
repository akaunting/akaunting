<?php

return [

    'invoice_number'    => 'Број фактуре',
    'invoice_date'      => 'Датум Фактуре',
    'total_price'       => 'Укупна цена',
    'due_date'          => 'Датум доспећа',
    'order_number'      => 'Број поруџбенице',
    'bill_to'           => 'Наплатити',

    'quantity'          => 'Количина',
    'price'             => 'Цена',
    'sub_total'         => 'Међузбир',
    'discount'          => 'Попуст',
    'tax_total'         => 'Порез укупно',
    'total'             => 'Укупно',

    'item_name'         => 'Име ставке|Имена ставки',

    'show_discount'     => ':discount% попуст',
    'add_discount'      => 'Додај попуст',
    'discount_desc'     => 'од међузбира',

    'payment_due'       => 'Доспећа плаћања',
    'paid'              => 'Плаћено',
    'histories'         => 'Историје',
    'payments'          => 'Плаћања',
    'add_payment'       => 'Додај плаћање',
    'mark_paid'         => 'Означи као плаћено',
    'mark_sent'         => 'Означи као послато',
    'download_pdf'      => 'Преузмите ПДФ',
    'send_mail'         => 'Пошаљи е-пошту',
    'all_invoices'      => 'Улогуј се да би видели све фактуре',

    'status' => [
        'draft'         => 'Скица',
        'sent'          => 'Послато',
        'viewed'        => 'Погледано',
        'approved'      => 'Одобрено',
        'partial'       => 'Делимично',
        'paid'          => 'Плаћено',
    ],

    'messages' => [
        'email_sent'     => 'Е-пошта за рачун је успешно послат!',
        'marked_sent'    => 'Рачун је успешно означен као послат!',
        'email_required' => 'Нема адресе е-поште за овог купца!',
        'draft'          => 'Ово је <b>ПРОФАКТУРА</b> и одразиће се на графикон након што буде примљен рачун.',

        'status' => [
            'created'   => 'Формирана на :date',
            'send'      => [
                'draft'     => 'Није послато',
                'sent'      => 'Послато :date',
            ],
            'paid'      => [
                'await'     => 'Чека наплату',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Примили сте ову е-пошту јер имате долазну фактуру од :amount за :customer.',
        'button'        => 'Платите сада',
    ],

];
