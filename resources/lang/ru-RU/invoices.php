<?php

return [

    'invoice_number'    => 'Номер квитанции',
    'invoice_date'      => 'Дата квитанции',
    'total_price'       => 'Общая цена',
    'due_date'          => 'Дата окончания',
    'order_number'      => 'Номер заказа',
    'bill_to'           => 'Плательщик',

    'quantity'          => 'Количество',
    'price'             => 'Цена',
    'sub_total'         => 'Итого',
    'discount'          => 'Discount',
    'tax_total'         => 'Итого с налогом',
    'total'             => 'Всего',

    'item_name'         => 'Имя пункта | Имена пунктов',

    'show_discount'     => ':discount% Discount',
    'add_discount'      => 'Add Discount',
    'discount_desc'     => 'of subtotal',

    'payment_due'       => 'Оплатить до',
    'paid'              => 'Оплачено',
    'histories'         => 'Истории',
    'payments'          => 'Платежи',
    'add_payment'       => 'Добавить платёж',
    'mark_paid'         => 'Пометить как оплачено',
    'mark_sent'         => 'Пометить как отправлено',
    'download_pdf'      => 'Скачать PDF',
    'send_mail'         => 'Отправить E-mail',

    'status' => [
        'draft'         => 'Черновик',
        'sent'          => 'Отправлено',
        'viewed'        => 'Просмотрено',
        'approved'      => 'Утверждено',
        'partial'       => 'Частично',
        'paid'          => 'Оплачено',
    ],

    'messages' => [
        'email_sent'     => 'Счет-фактура успешно отправлена на e-mail!',
        'marked_sent'    => 'Счет-фактура помечена как успешно отправлена!',
        'email_required' => 'Отсутствует e-mail адрес для этого клиента!',
    ],

    'notification' => [
        'message'       => 'Вы получили это письмо потому, что у Вас имеются входящие :amount счета на :customer клиента.',
        'button'        => 'Оплатить сейчас',
    ],

];
