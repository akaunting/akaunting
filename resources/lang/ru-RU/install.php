<?php

return [

    'next'                  => 'Далее',
    'refresh'               => 'Обновить',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
        'language'          => 'Шаг 1/3: Выбор языка',
        'database'          => 'Шаг 2/3: Настройка базы данных',
        'settings'          => 'Шаг 3/3: Компании и данные Администратора',
    ],

    'language' => [
        'select'            => 'Выбрать язык',
    ],

    'requirements' => [
        'enabled'           => ':feature должно быть включено!',
        'disabled'          => ':feature должно быть отключено!',
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => ':directory директория должна быть доступна для записи!',
    ],

    'database' => [
        'hostname'          => 'Имя хоста',
        'username'          => 'Имя пользователя',
        'password'          => 'Пароль',
        'name'              => 'База данных',
    ],

    'settings' => [
        'company_name'      => 'Название компании',
        'company_email'     => 'E-mail компании',
        'admin_email'       => 'E-mail Администратора',
        'admin_password'    => 'Пароль Администратора',
    ],

    'error' => [
        'connection'        => 'Ошибка: не удалось подключиться к базе данных! Пожалуйста, убедитесь, что данные являются правильными.',
    ],

];
