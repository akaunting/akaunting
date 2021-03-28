<?php

return [

    'bill_number'       => 'Број рачуна',
    'bill_date'         => 'Датум рачуна',
    'total_price'       => 'Укупна цена',
    'due_date'          => 'Датум доспећа',
    'order_number'      => 'Број поруџбенице',
    'bill_from'         => 'Рачун од',

    'quantity'          => 'Количина',
    'price'             => 'Цена',
    'sub_total'         => 'Међузбир',
    'discount'          => 'Попуст',
    'tax_total'         => 'Укупно порез',
    'total'             => 'Укупно',

    'item_name'         => 'Назив ставке| Назив ставки',

    'show_discount'     => ':discount% попуста',
    'add_discount'      => 'Додај попуст',
    'discount_desc'     => 'од међузбира',

    'payment_due'       => 'Доспећа плаћања',
    'amount_due'        => 'Доспели износ',
    'paid'              => 'Плаћено',
    'histories'         => 'Историје',
    'payments'          => 'Плаћања',
    'add_payment'       => 'Додај плаћање',
    'mark_received'     => 'Означи као примљено',
    'download_pdf'      => 'Преузми ПДФ',
    'send_mail'         => 'Пошаљи Е-пошту',

    'statuses' => [
        'draft'         => 'Скица',
        'received'      => 'Примљено',
        'partial'       => 'Делимично',
        'paid'          => 'Плаћено',
    ],

    'messages' => [
        'received'      => 'Рачун означен као успешно примљен!',
        'draft'          => 'Ово је <b>ПРОФАКТУРА</b> и одразиће се на графикон након што буде примљен рачун.',

        'status' => [
            'created'   => 'Формирана на: датум',
            'receive'      => [
                'draft'     => 'Није послато',
                'received'  => 'Примљено дана: датум',
            ],
            'paid'      => [
                'await'     => 'Чекајући плаћања',
            ],
        ],
    ],

];
