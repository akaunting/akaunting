<?php

return [

    'success' => [
        'added'             => ':type добавлено!',
        'updated'           => ':type обновлено!',
        'deleted'           => ':type удалено!',
        'duplicated'        => ':type продублировано!',
        'imported'          => ':type импортировано!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Ошибка: Вы не можете управлять этой компанией!',
        'customer'          => 'Ошибка: Пользователь не создан! :name уже использует этот адрес электронной почты.',
        'no_file'           => 'Ошибка: Файл не выбран!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],
    'warning' => [
        'deleted'           => 'Предупреждение: Вы не можете удалить <b>:name</b> потому что имеется связь с :text.',
        'disabled'          => 'Предупреждение: Вы не можете отключить <b>:name</b> потому что имеется связь с :text.',
    ],

];
