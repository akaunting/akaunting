<?php

return [

    'payment_received'      => 'Получено плащане',
    'payment_made'          => 'Извършено плащане',
    'paid_by'               => 'Платено от',
    'paid_to'               => 'Платено на',
    'related_invoice'       => 'Свързана фактура',
    'related_bill'          => 'Свързана фактура',
    'recurring_income'      => 'Повтарящ се приход',
    'recurring_expense'     => 'Повтарящ се разход',
    'included_tax'          => 'Включен ДДС',
    'connected'             => 'Свързано',
    'connect_message'       => 'Данъците за този :type не бяха изчислени по време на свързването. Данъците не могат да бъдат свързани.',

    'form_description' => [
        'general'           => 'Тук можете да въведете общата информация за транзакцията като дата, сума, описание и др.',
        'assign_income'     => 'Изберете категория и клиент, за да направите отчетите си по-подробни.',
        'assign_expense'    => 'Изберете категория и клиент, за да направите отчетите си по-подробни.',
        'other'             => 'Въведете номер и препратка, за да поддържате транзакцията свързана с вашите записи.',
    ],

    'slider' => [
        'create'            => ':user създаде тази транзакция на :date',
        'attachments'       => 'Изтеглете файлове, приложени към тази транзакция',
        'create_recurring'  => ':user създаде този шаблон за повтарящи се транзакции на :date',
        'schedule'          => 'Повтори на всеки :interval :frequency от :date
',
        'children'          => ':count транзакции бяха автоматично създадени',
        'connect'           => 'Тази транзакция е свързана с :count транзакции',
        'transfer_headline' => '<div> <span class="font-bold"> От: </span> :from_account </div> <div> <span class="font-bold"> до: </span> :to_account </div>',
        'transfer_desc'     => 'Трансфер, създаден на :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Вашият клиент може да прегледа транзакцията на този линк',
            'copy_link'     => 'Копирайте връзката и я споделете с вашия клиент.',
        ],

        'expense' => [
            'show_link'     => 'Вашият доставчик може да прегледа транзакцията на този линк',
            'copy_link'     => 'Копирайте връзката и я споделете с вашия доставчик.',
        ],
    ],

    'sticky' => [
        'description'       => 'Преглеждате как вашият клиент ще види уеб версията на вашето плащане.',
    ],

    'messages' => [
        'update_document_transaction' => 'Можете да актуализирате тази транзакция. Отидете в документа и я редактирайте там.',
        'create_document_transaction_error' => 'Тази крайна точка не може да бъде добавена към документ. Използвайте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Тази крайна точка не може да бъде актуализирана към документ. Използвайте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Тази крайна точка не може да бъде изтрита от документ. Използвайте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
