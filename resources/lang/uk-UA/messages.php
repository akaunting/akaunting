<?php

return [

    'success' => [
        'added'             => ': тип додано!',
        'updated'           => ': тип оновлено!',
        'deleted'           => ': тип видалено!',
        'duplicated'        => ': тип продубльовано!',
        'imported'          => ': тип імпортовано!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Помилка: Вам не дозволено керувати цією компанією!',
        'customer'          => 'Помилка: Користувача не створено! : ця електронна адреса вже використовується.',
        'no_file'           => 'Помилка: Файл не обрано!',
        'last_category'     => 'Помилка: Неможливо видалити :type категорію!',
        'invalid_token'     => 'Помилка: Введений токен невірний!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],
    'warning' => [
        'deleted'           => 'Увага: Вам не дозволено видалити <b>: ім\'я</b> , оскільки воно має: текст, пов\'язані.',
        'disabled'          => 'Увага: Вам не дозволяється відключати <b>: ім\'я</b> , оскільки воно має: текст, пов\'язані.',
    ],

];
