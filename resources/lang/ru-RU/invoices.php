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
    'tax_total'         => 'Итого с налогом',
    'total'             => 'Всего',

    'item_name'         => 'Имя пункта | Имена пунктов',

    'payment_due'       => 'Оплатить до',
    'paid'              => 'Оплачено',
    'histories'         => 'Истории',
    'payments'          => 'Платежи',
    'add_payment'       => 'Добавить платёж',
    'mark_paid'         => 'Mark Paid',
    'mark_sent'         => 'Mark Sent',
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
        'email_sent'     => 'Invoice email has been sent successfully!',
        'marked_sent'    => 'Invoice marked as sent successfully!',
        'email_required' => 'No email address for this customer!',
    ],

    'notification' => [
        'message'       => 'You are receiving this email because you have an upcoming :amount invoice to :customer customer.',
        'button'        => 'Pay Now',
    ],

];
