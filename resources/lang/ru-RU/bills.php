<?php

return [

    'bill_number'           => 'Номер закупки',
    'bill_date'             => 'Дата закупки',
    'total_price'           => 'Общая стоимость',
    'due_date'              => 'Дата завершения',
    'order_number'          => 'Номер заказа',
    'bill_from'             => 'Закупка от',

    'quantity'              => 'Количество',
    'price'                 => 'Цена',
    'sub_total'             => 'Итого',
    'discount'              => 'Скидка',
    'item_discount'         => 'Скидка на строку',
    'tax_total'             => 'Итого с налогом',
    'total'                 => 'Всего',

    'item_name'             => 'Имя пункта | Имена пунктов',

    'show_discount'         => ':discount% Скидка',
    'add_discount'          => 'Добавить скидку',
    'discount_desc'         => 'от итога',

    'payment_due'           => 'Оплатить до',
    'amount_due'            => 'Сумма',
    'paid'                  => 'Оплачено',
    'histories'             => 'Истории',
    'payments'              => 'Платежи',
    'add_payment'           => 'Добавить платёж',
    'mark_paid'             => 'Отметить как оплачено',
    'mark_received'         => 'Отметить как получено',
    'mark_cancelled'        => 'Отметить как отменено',
    'download_pdf'          => 'Скачать PDF',
    'send_mail'             => 'Отправить E-mail',
    'create_bill'           => 'Создать закупку',
    'receive_bill'          => 'Получить закупку',
    'make_payment'          => 'Сделать оплату',

    'messages' => [
        'draft'             => 'Это <b>ЧЕРНОВИК</b> закупки, он будет проведен после отправки.',

        'status' => [
            'created'       => 'Создано :date',
            'receive' => [
                'draft'     => 'Не отправлен',
                'received'  => 'Получено :date',
            ],
            'paid' => [
                'await'     => 'Ожидает оплаты',
            ],
        ],
    ],

];
