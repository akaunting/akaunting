<?php

return [

    'success' => [
        'added'             => ':type добавлено!',
        'updated'           => ':type обновлено!',
        'deleted'           => ':type удалено!',
        'duplicated'        => ':type duplicated!',
        'imported'          => ':type imported!',
    ],
    'error' => [
        'payment_add'       => 'Error: You can not add payment! You should check add amount.',
        'not_user_company'  => 'Ошибка: Вы не можете управлять этой компанией!',
        'customer'          => 'Error: You can not created user! :name use this email address.',
        'no_file'           => 'Error: No file selected!',
    ],
    'warning' => [
        'deleted'           => 'Предупреждение: Вы не можете удалить <b>:name</b> потому что имеется связь с :text.',
        'disabled'          => 'Предупреждение: Вы не можете отключить <b>:name</b> потому что имеется связь с :text.',
    ],

];
