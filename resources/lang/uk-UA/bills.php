<?php

return [

    'bill_number'       => 'Номер рахунку',
    'bill_date'         => 'Дата рахунку',
    'total_price'       => 'Загальна сума',
    'due_date'          => 'Термін дії',
    'order_number'      => 'Номер замовлення',
    'bill_from'         => 'Рахунок від',

    'quantity'          => 'Кількість',
    'price'             => 'Ціна',
    'sub_total'         => 'Всього',
    'discount'          => 'Знижка',
    'tax_total'         => 'Всього пдв',
    'total'             => 'Всього',

    'item_name'         => 'Назва товару',

    'show_discount'     => ': знижка % Знижка',
    'add_discount'      => 'Додати знижку',
    'discount_desc'     => 'Підсумок',

    'payment_due'       => 'Очікуваний платіж',
    'amount_due'        => 'Заборгованість',
    'paid'              => 'Оплачено',
    'histories'         => 'Історії',
    'payments'          => 'Платежі',
    'add_payment'       => 'Додати платіж',
    'mark_received'     => 'Відмітка отримана',
    'download_pdf'      => 'Завантажити PDF',
    'send_mail'         => 'Надіслати листа',

    'status' => [
        'draft'         => 'Чернетка',
        'received'      => 'Отримано',
        'partial'       => 'Частково',
        'paid'          => 'Оплачений',
    ],

    'messages' => [
        'received'      => 'Рахунок позначено як успішно отриманий!',
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
