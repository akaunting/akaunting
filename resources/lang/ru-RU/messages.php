<?php

return [

    'success' => [
        'added'             => ':type добавлено!',
        'updated'           => ':type обновлено!',
        'deleted'           => ':type удалено!',
        'duplicated'        => ':type продублировано!',
        'imported'          => ':type импортировано!',
    ],
    'error' => [
        'over_payment'      => 'Ошибка: Оплата не добавлена! Сумма проходит, как общая.',
        'not_user_company'  => 'Ошибка: Вы не можете управлять этой компанией!',
        'customer'          => 'Ошибка: Пользователь не создан! :name уже использует этот адрес электронной почты.',
        'no_file'           => 'Ошибка: Файл не выбран!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Предупреждение: Вы не можете удалить <b>:name</b> потому что имеется связь с :text.',
        'disabled'          => 'Предупреждение: Вы не можете отключить <b>:name</b> потому что имеется связь с :text.',
    ],

];
