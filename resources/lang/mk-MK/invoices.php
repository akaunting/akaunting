<?php

return [

    'invoice_number'    => 'Број на Фактура',
    'invoice_date'      => 'Датум на Фактура',
    'total_price'       => 'Вкупна цена',
    'due_date'          => 'Доспева на',
    'order_number'      => 'Број на нарачка',
    'bill_to'           => 'Фактурирај на',

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
    'paid'              => 'Платено',
    'histories'         => 'Историја',
    'payments'          => 'Плаќања',
    'add_payment'       => 'Додади плаќање',
    'mark_paid'         => 'Означи платено',
    'mark_sent'         => 'Означи испратено',
    'download_pdf'      => 'Превземи PDF',
    'send_mail'         => 'Прати е-маил',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Неиспратено',
        'sent'          => 'Испратена',
        'viewed'        => 'Прегледано',
        'approved'      => 'Одобрено',
        'partial'       => 'Некомплетно',
        'paid'          => 'Платено',
    ],

    'messages' => [
        'email_sent'     => 'Е-маил порака со фактура е испратена успешно!',
        'marked_sent'    => 'Е-маил порака со фактура е испратена успешно!',
        'email_required' => 'Не постои е-маил адреса за овој клиент!',
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
        'message'       => 'Ја довите оваа порака затоа што имате претстојна :amount invoice to :customer customer.',
        'button'        => 'Плати сега',
    ],

];
