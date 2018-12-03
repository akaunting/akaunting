<?php

return [

    'invoice_number'    => 'Номер рахунку',
    'invoice_date'      => 'Дата рахунку',
    'total_price'       => 'Загальна сума',
    'due_date'          => 'Термін дії',
    'order_number'      => 'Номер замовлення',
    'bill_to'           => 'Рахунок-фактура',

    'quantity'          => 'Кількість',
    'price'             => 'Ціна',
    'sub_total'         => 'Всього',
    'discount'          => 'Знижка',
    'tax_total'         => 'Всього пдв',
    'total'             => 'Всього',

    'item_name'         => 'Назва товару',

    'show_discount'     => ': знижка % Знижка',
    'add_discount'      => 'Додати знижку',
    'discount_desc'     => 'підсумок',

    'payment_due'       => 'Очікуваний платіж',
    'paid'              => 'Оплачено',
    'histories'         => 'Історії',
    'payments'          => 'Платежі',
    'add_payment'       => 'Додати платіж',
    'mark_paid'         => 'Позначити оплату',
    'mark_sent'         => 'Позначити відправлено',
    'download_pdf'      => 'Завантажити PDF',
    'send_mail'         => 'Надіслати листа',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Чернетка',
        'sent'          => 'Надіслано',
        'viewed'        => 'Переглянуті',
        'approved'      => 'Затверджено',
        'partial'       => 'Частково',
        'paid'          => 'Оплачено',
    ],

    'messages' => [
        'email_sent'     => 'Повідомлення з рахунком було успішно відправлено!',
        'marked_sent'    => 'Повідомлення з рахунком було успішно відправлено!',
        'email_required' => 'Немає повідомлень цього клієнта!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Ви отримуєте цей лист, тому що у вас є наступна: сума рахунку до: клієнта замовника.',
        'button'        => 'Оплатити зараз',
    ],

];
