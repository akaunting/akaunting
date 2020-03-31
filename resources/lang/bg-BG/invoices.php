<?php

return [

    'invoice_number'        => 'Номер на фактура',
    'invoice_date'          => 'Дата на фактура',
    'total_price'           => 'Обща цена',
    'due_date'              => 'Падежна дата',
    'order_number'          => 'Номер на поръчка',
    'bill_to'               => 'Издадена на',

    'quantity'              => 'Количество',
    'price'                 => 'Цена',
    'sub_total'             => 'Междинна сума',
    'discount'              => 'Отстъпка',
    'item_discount'         => 'Отстъпка за реда.',
    'tax_total'             => 'Общо данък',
    'total'                 => 'Общо',

    'item_name'             => 'Име на артикул | Имена на артикули',

    'show_discount'         => ':discount% отстъпка',
    'add_discount'          => 'Добави отстъпка',
    'discount_desc'         => 'на междинна сума',

    'payment_due'           => 'Падежна дата',
    'paid'                  => 'Платен',
    'histories'             => 'История',
    'payments'              => 'Плащания',
    'add_payment'           => 'Добавяне на плащане',
    'mark_paid'             => 'Отбележи като платено',
    'mark_sent'             => 'Маркирай като изпратено',
    'mark_viewed'           => 'Отбележи като прегледано',
    'mark_cancelled'        => 'Отбележи като канселирана.',
    'download_pdf'          => 'Изтегляне на PDF',
    'send_mail'             => 'Изпращане на имейл',
    'all_invoices'          => 'Вход за да видите всички фактури',
    'create_invoice'        => 'Създай фактура',
    'send_invoice'          => 'Изпрати фактура',
    'get_paid'              => 'Получи плащане',
    'accept_payments'       => 'Приеми на онлайн плащане',

    'statuses' => [
        'draft'             => 'Чернова',
        'sent'              => 'Изпратено',
        'viewed'            => 'Разгледани',
        'approved'          => 'Одобрени',
        'partial'           => 'Частично',
        'paid'              => 'Платено',
        'overdue'           => 'Просрочен',
        'unpaid'            => 'Неплатени',
        'cancelled'         => 'Отказано',
    ],

    'messages' => [
        'email_sent'        => 'Фактурата беше изпратена!',
        'marked_sent'       => 'Фактурата е маркирана като изпратена!',
        'marked_paid'       => 'Фактурата е маркирана като платена!',
        'marked_viewed'     => 'Invoice marked as viewed!',
        'marked_cancelled'  => 'Invoice marked as cancelled!',
        'email_required'    => 'Няма имейл адрес за този клиент!',
        'draft'             => 'Това е <b>ЧЕРНОВА</b> фактура и няма да бъде отразена в графиките след като бъде изпратена.',

        'status' => [
            'created'       => 'Създадено на :date',
            'viewed'        => 'Разгледан',
            'send' => [
                'draft'     => 'Не е изпратено',
                'sent'      => 'Изпратено на :date',
            ],
            'paid' => [
                'await'     => 'Очакващо плащане',
            ],
        ],
    ],

];
