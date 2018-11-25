<?php

return [

    'bill_number'       => 'Број на сметка',
    'bill_date'         => 'Датум на сметка',
    'total_price'       => 'Вкупна цена',
    'due_date'          => 'Доспева на',
    'order_number'      => 'Број на нарачка',
    'bill_from'         => 'Сметка од',

    'quantity'          => 'Количина',
    'price'             => 'Цена',
    'sub_total'         => 'Меѓузбир',
    'discount'          => 'Попуст',
    'tax_total'         => 'Вкупно данок',
    'total'             => 'Вкупно',

    'item_name'         => 'Име / Имиња',

    'show_discount'     => ':discount% Попуст',
    'add_discount'      => 'Додади попуст',
    'discount_desc'     => 'од меѓузбир',

    'payment_due'       => 'Доспева за плаќање',
    'amount_due'        => 'Износ за плаќање',
    'paid'              => 'Платено',
    'histories'         => 'Историја',
    'payments'          => 'Плаќања',
    'add_payment'       => 'Додади плаќање',
    'mark_received'     => 'Означи како примена',
    'download_pdf'      => 'Превземи PDF',
    'send_mail'         => 'Прати е-маил',

    'status' => [
        'draft'         => 'Неиспратено',
        'received'      => 'Примено',
        'partial'       => 'Некомплетно',
        'paid'          => 'Платено',
    ],

    'messages' => [
        'received'      => 'Сметката е означена како примена успешно!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
