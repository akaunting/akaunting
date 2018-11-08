<?php

return [

    'next'                  => 'Наступний',
    'refresh'               => 'Перезавантажити',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
        'language'          => 'Крок 1/3: Вибір мови',
        'database'          => 'Крок 2/3: Налаштування бази даних',
        'settings'          => 'Крок 3/3: Деталі компанії та адміністратора',
    ],

    'language' => [
        'select'            => 'Виберіть мову',
    ],

    'requirements' => [
        'enabled'           => ': функцію потрібно ввімкнути!',
        'disabled'          => ': функцію потрібно вимкнути!',
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => ': директорія каталогу повинна бути доступною для запису!',
    ],

    'database' => [
        'hostname'          => 'Ім\'я хосту',
        'username'          => 'Ім\'я користувача',
        'password'          => 'Пароль',
        'name'              => 'База Даних',
    ],

    'settings' => [
        'company_name'      => 'Назва компанії',
        'company_email'     => 'Email Вашої Компанії',
        'admin_email'       => 'Адміністратор електронної пошти',
        'admin_password'    => 'Пароль адміністратора',
    ],

    'error' => [
        'connection'        => 'Помилка: Не вдалося підключитися до бази даних! Переконайтесь, що даніі є правильними.',
    ],

];
