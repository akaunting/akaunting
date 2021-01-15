<?php

return [

    'bill_number'           => 'Номер на фактурата',
    'bill_date'             => 'Дата фактура',
    'total_price'           => 'Обща цена',
    'due_date'              => 'Падежна дата',
    'order_number'          => 'Номер на поръчка',
    'bill_from'             => 'Фактура от',

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

    'payment_due'           => 'Дължимото плащане',
    'amount_due'            => 'Дължимата сума',
    'paid'                  => 'Платени',
    'histories'             => 'История',
    'payments'              => 'Плащания',
    'add_payment'           => 'Добавяне на плащане',
    'mark_paid'             => 'Маркирай като платен',
    'mark_received'         => 'Отбелязване като приета',
    'mark_cancelled'        => 'Отбележи като канселирана.',
    'download_pdf'          => 'Изтегляне на PDF',
    'send_mail'             => 'Изпращане на имейл',
    'create_bill'           => 'Създай сметка',
    'receive_bill'          => 'Приеми фактура',
    'make_payment'          => 'Направи плащане',

    'messages' => [
        'draft'             => 'Това е <b>ЧЕРНОВА</b> фактура и няма да бъде отразена в графиките след като бъде получена.',

        'status' => [
            'created'       => 'Създадено на :date',
            'receive' => [
                'draft'     => 'Не е одобрена',
                'received'  => 'Приета на :date',
            ],
            'paid' => [
                'await'     => 'Очакващо плащане',
            ],
        ],
    ],

];
