<?php

return [

    'invoice_number'        => 'Номер счёта',
    'invoice_date'          => 'Дата квитанции',
    'total_price'           => 'Общая цена',
    'due_date'              => 'Дата окончания',
    'order_number'          => 'Номер заказа',
    'bill_to'               => 'Плательщик',

    'quantity'              => 'Количество',
    'price'                 => 'Цена',
    'sub_total'             => 'Итого',
    'discount'              => 'Скидка',
    'tax_total'             => 'Итого с налогом',
    'total'                 => 'Всего',

    'item_name'             => 'Имя пункта | Имена пунктов',

    'show_discount'         => ':discount% Скидка',
    'add_discount'          => 'Добавить скидку',
    'discount_desc'         => 'от итога',

    'payment_due'           => 'Оплатить до',
    'paid'                  => 'Оплачено',
    'histories'             => 'Истории',
    'payments'              => 'Платежи',
    'add_payment'           => 'Добавить платёж',
    'mark_paid'             => 'Пометить как оплачено',
    'mark_sent'             => 'Пометить как отправлено',
    'download_pdf'          => 'Скачать PDF',
    'send_mail'             => 'Отправить E-mail',
    'all_invoices'          => 'Войти для просмотра всех счетов',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'Черновик',
        'sent'              => 'Отправлено',
        'viewed'            => 'Просмотрено',
        'approved'          => 'Утверждено',
        'partial'           => 'Частично',
        'paid'              => 'Оплачено',
    ],

    'messages' => [
        'email_sent'        => 'Счет-фактура успешно отправлена на e-mail!',
        'marked_sent'       => 'Счет-фактура помечена как успешно отправлена!',
        'email_required'    => 'Отсутствует e-mail адрес для этого клиента!',
        'draft'             => 'Это <b>ЧЕРНОВИК</b> счета, он будет проведен после отправки.',

        'status' => [
            'created'       => 'Создано :date',
            'send' => [
                'draft'     => 'Не отправлено',
                'sent'      => 'Отправлено :date',
            ],
            'paid' => [
                'await'     => 'Ожидает оплаты',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Вы получили это письмо потому, что у Вас имеются входящие :amount счета на :customer клиента.',
        'button'            => 'Оплатить сейчас',
    ],

];
