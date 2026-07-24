<?php

return [

    'payment_received'      => 'Примено плаќање',
    'payment_made'          => 'Извршено плаќање',
    'paid_by'               => 'Платено од',
    'paid_to'               => 'Платено на',
    'related_invoice'       => 'Поврзана фактура',
    'related_bill'          => 'Поврзана сметка',
    'recurring_income'      => 'Повторувачки приход',
    'recurring_expense'     => 'Повторувачки трошок',
    'included_tax'          => 'Вклучен даночен износ',
    'connected'             => 'Поврзано',
    'connect_message'       => 'Даноците за овој :type не беа пресметани при процесот на поврзување. Данокот не може да се поврзе.',

    'form_description' => [
        'general'           => 'Овде можете да ги внесете општите информации за трансакцијата како датум, износ, сметка, опис итн.',
        'assign_income'     => 'Изберете категорија и клиент за да ги направите вашите извештаи подетални.',
        'assign_expense'    => 'Изберете категорија и добавувач за да ги направите вашите извештаи подетални.',
        'other'             => 'Внесете број и референца за да ја задржите трансакцијата поврзана со вашата евиденција.',
    ],

    'slider' => [
        'create'            => ':user ја создаде оваа трансакција на :date',
        'attachments'       => 'Преземете ги документите прикачени на оваа трансакција',
        'create_recurring'  => ':user го создаде овој повторувачки шаблон на :date',
        'schedule'          => 'Повторувај секој :interval :frequency од :date',
        'children'          => ':count трансакции беа создадени автоматски',
        'connect'           => 'Оваа трансакција е поврзана со :count трансакции',
        'transfer_headline' => '<div> <span class="font-bold"> Од: </span> :from_account </div> <div> <span class="font-bold"> До: </span> :to_account </div>',
        'transfer_desc'     => 'Трансакцијата е креирана на :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Вашиот клиент може да ја види трансакцијата на овој линк',
            'copy_link'     => 'Копирајте го линкот и споделете го со вашиот клиент.',
        ],

        'expense' => [
            'show_link'     => 'Вашиот добавувач може да ја види трансакцијата на овој линк',
            'copy_link'     => 'Копирајте го линкот и споделете го со вашиот добавувач.',
        ],
    ],

    'sticky' => [
        'description'       => 'Прегледувате како вашиот клиент ќе ја види веб-верзијата на вашата уплата.',
    ],

    'messages' => [
        'update_document_transaction' => 'Можете да ја ажурирате оваа трансакција. Одете во документот и уредете ја таму.',
        'create_document_transaction_error' => 'Оваа крајна точка не може да се додаде во документ. Користете {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Оваа крајна точка не може да се ажурира во документ. Користете {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Оваа крајна точка не може да се избрише од документ. Користете {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
