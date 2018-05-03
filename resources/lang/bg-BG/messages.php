<?php

return [

    'success' => [
        'added'             => ':type добавен!',
        'updated'           => ':type променен!',
        'deleted'           => ':type изтрит!',
        'duplicated'        => ':type дублиран!',
        'imported'          => ':type импортиран!',
    ],
    'error' => [
        'over_payment'      => 'Грешка: Плащането не е добавено! Сумата преминава общата сума.',
        'not_user_company'  => 'Грешка: Не ви е позволено да управлявате тази компания!',
        'customer'          => 'Грешка: Потребителят не е създаден! :name вече използва този имейл адрес.',
        'no_file'           => 'Грешка: Няма избран файл!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Предупреждение: Не ви е позволено да изтриете <b>:name</b>, защото има :text свързан.',
        'disabled'          => 'Предупреждение: Не ви е позволено да деактивирате <b>:name</b>, защото има :text свързан.',
    ],

];
