<?php

return [

    'bill_number'           => 'Номер рахунку',
    'bill_date'             => 'Дата рахунку',
    'total_price'           => 'Загальна сума',
    'due_date'              => 'Термін дії',
    'order_number'          => 'Номер замовлення',
    'bill_from'             => 'Рахунок від',

    'quantity'              => 'Кількість',
    'price'                 => 'Ціна',
    'sub_total'             => 'Всього',
    'discount'              => 'Знижка',
    'tax_total'             => 'Всього пдв',
    'total'                 => 'Всього',

    'item_name'             => 'Назва товару',

    'show_discount'         => ':discount% Знижка',
    'add_discount'          => 'Додати знижку',
    'discount_desc'         => 'Сума',

    'payment_due'           => 'Очікуваний платіж',
    'amount_due'            => 'Заборгованість',
    'paid'                  => 'Оплачено',
    'histories'             => 'Історії',
    'payments'              => 'Платежі',
    'add_payment'           => 'Додати платіж',
    'mark_received'         => 'Відмітка Отримано',
    'download_pdf'          => 'Завантажити PDF',
    'send_mail'             => 'Надіслати листа',
    'create_bill'           => 'Створити рахунок',
    'receive_bill'          => 'Отримати рахунок',
    'make_payment'          => 'Оплатити',

    'statuses' => [
        'draft'             => 'Чернетка',
        'received'          => 'Отримано',
        'partial'           => 'Частково оплачено',
        'paid'              => 'Оплачено',
        'overdue'           => 'Протерміновано',
        'unpaid'            => 'Неоплачено',
    ],

    'messages' => [
        'received'          => 'Рахунок позначено як успішно отриманий!',
        'draft'             => 'Цей рахунок <b>DRAFT</b> відображатиметься на графіках після його отримання.',

        'status' => [
            'created'       => 'Створено :date',
            'receive' => [
                'draft'     => 'Не надіслано',
                'received'  => 'Отримано :date',
            ],
            'paid' => [
                'await'     => 'Очікується оплата',
            ],
        ],
    ],

];
