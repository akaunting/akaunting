<?php

return [

    'next'                  => 'Напред',
    'refresh'               => 'Освежи',

    'steps' => [
        'requirements'      => 'Потребно је да испуните следеће услове!',
        'language'          => 'Корак 1/3: Избор језика',
        'database'          => 'Корак 2/3: Поставка базе података',
        'settings'          => 'Корак 3/3: Фирма и админ детаљи',
    ],

    'language' => [
        'select'            => 'Изаберите језик',
    ],

    'requirements' => [
        'enabled'           => ':feature мора бити омогућено!',
        'disabled'          => ':feature мора бити онемогућено!',
        'extension'         => ':extension проширење треба да буде учитано!',
        'directory'         => ':directory дирекотријум мора бити омогућен за уписивање!',
    ],

    'database' => [
        'hostname'          => 'Назив хоста',
        'username'          => 'Корисничко име',
        'password'          => 'Лозинка',
        'name'              => 'База података',
    ],

    'settings' => [
        'company_name'      => 'Назив фирме',
        'company_email'     => 'E-пошта фирме',
        'admin_email'       => 'Aдмин e-пошта',
        'admin_password'    => 'Aдмин лозинка',
    ],

    'error' => [
        'connection'        => 'Грешка : Није могуће повезати се на базу података! Проверите јесу ли подаци исправни.',
    ],

];
