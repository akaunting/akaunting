<?php

return [

    'payment_received'      => 'Платёж получен',
    'payment_made'          => 'Платёж осуществлён',
    'paid_by'               => 'Плательщик',
    'paid_to'               => 'Получатель',
    'related_invoice'       => 'Связанный счёт',
    'related_bill'          => 'Связанный счёт на оплату',
    'recurring_income'      => 'Повторяющийся доход',
    'recurring_expense'     => 'Повторяющийся расход',
    'included_tax'          => 'Сумма включённого налога',
    'connected'             => 'Подключено',
    'connect_message'       => 'Налоги для данной :type не были рассчитаны во время процесса подключения. Налоги не могут быть подключены.',

    'form_description' => [
        'general'           => 'Здесь вы можете ввести общую информацию о транзакции, такую как дата, сумма, счёт, описание и т. д.',
        'assign_income'     => 'Выберите категорию и клиента, чтобы сделать ваши отчёты более детализированными.',
        'assign_expense'    => 'Выберите категорию и поставщика, чтобы сделать ваши отчёты более детализированными.',
        'other'             => 'Введите номер и ссылку, чтобы сохранить связь транзакции с вашими записями.',
    ],

    'slider' => [
        'create'            => ':user создал эту транзакцию :date',
        'attachments'       => 'Загрузить файлы, прикреплённые к этой транзакции',
        'create_recurring'  => ':user создал этот шаблон повторения :date',
        'schedule'          => 'Повторять каждые :interval :frequency начиная с :date',
        'children'          => ':count транзакций были созданы автоматически',
        'connect'           => 'Эта транзакция связана с :count транзакциями',
        'transfer_headline' => '<div> <span class="font-bold"> От: </span> :from_account </div> <div> <span class="font-bold"> Кому: </span> :to_account </div>',
        'transfer_desc'     => 'Перевод создан :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Ваш клиент может просмотреть транзакцию по этой ссылке',
            'copy_link'     => 'Скопируйте ссылку и поделитесь ею с вашим клиентом.',
        ],

        'expense' => [
            'show_link'     => 'Ваш поставщик может просмотреть транзакцию по этой ссылке',
            'copy_link'     => 'Скопируйте ссылку и поделитесь ею с вашим поставщиком.',
        ],
    ],

    'sticky' => [
        'description'       => 'Вы просматриваете, как ваш клиент увидит веб-версию вашего платежа.',
    ],

    'messages' => [
        'update_document_transaction' => 'Вы можете обновить эту транзакцию. Перейдите к документу и отредактируйте его там.',
        'create_document_transaction_error' => 'Эта конечная точка не может быть добавлена к документу. Используйте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Эта конечная точка не может быть обновлена для документа. Используйте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Эта конечная точка не может быть удалена для документа. Используйте {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
