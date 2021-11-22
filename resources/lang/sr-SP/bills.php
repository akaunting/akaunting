<?php

return [

    'bill_number'           => 'Број рачуна',
    'bill_date'             => 'Датум рачуна',
    'bill_amount'           => 'Износ рачуна',
    'total_price'           => 'Укупна цена',
    'due_date'              => 'Датум доспећа',
    'order_number'          => 'Број поруџбенице',
    'bill_from'             => 'Рачун од',

    'quantity'              => 'Количина',
    'price'                 => 'Цена',
    'sub_total'             => 'Међузбир',
    'discount'              => 'Попуст',
    'item_discount'         => 'Линијски попуст',
    'tax_total'             => 'Укупно порез',
    'total'                 => 'Укупно',

    'item_name'             => 'Назив ставке|Назив ставки',

    'show_discount'         => ':discount% попуста',
    'add_discount'          => 'Додај попуст',
    'discount_desc'         => 'од међузбира',

    'payment_due'           => 'Доспећа плаћања',
    'amount_due'            => 'Доспели износ',
    'paid'                  => 'Плаћено',
    'histories'             => 'Историје',
    'payments'              => 'Плаћања',
    'add_payment'           => 'Додај плаћање',
    'mark_paid'             => 'Означи као плаћено',
    'mark_received'         => 'Означи као примљено',
    'mark_cancelled'        => 'Означити као отказано ',
    'download_pdf'          => 'Преузми ПДФ',
    'send_mail'             => 'Пошаљи Е-пошту',
    'create_bill'           => 'Направи фактуру',
    'receive_bill'          => 'Прими фактуру',
    'make_payment'          => 'Плати',

    'messages' => [
        'draft'             => 'Ово је <b>ПРОФАКТУРА</b> и одразиће се на графикон након што буде примљен рачун.',

        'status' => [
            'created'       => 'Формирана на :date',
            'receive' => [
                'draft'     => 'Није примљено',
                'received'  => 'Примљено дана :date',
            ],
            'paid' => [
                'await'     => 'Чекајући плаћања',
            ],
        ],
    ],

];
